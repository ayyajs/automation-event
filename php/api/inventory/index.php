<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

include_once '../../config/database.php';
include_once '../../config/jwt.php';

// Authenticate user
$jwt = new JWTHandler();
$token = getBearerToken();

if (!$token) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Token not provided."));
    exit();
}

$user = $jwt->getUserFromToken($token);
if (!$user) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Invalid token."));
    exit();
}

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        handleGet($db);
        break;
    case 'POST':
        handlePost($db, $data, $user);
        break;
    case 'PUT':
        handlePut($db, $data, $user);
        break;
    case 'DELETE':
        handleDelete($db);
        break;
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method not allowed"));
        break;
}

function handleGet($db) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $lowStock = isset($_GET['low_stock']) ? $_GET['low_stock'] : '';
    $offset = ($page - 1) * $limit;

    // Build where conditions
    $whereConditions = array();
    $params = array();
    
    if ($search) {
        $whereConditions[] = "(i.name LIKE ? OR i.product_code LIKE ? OR i.sku LIKE ?)";
        $searchParam = "%{$search}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    if ($category) {
        $whereConditions[] = "i.category_id = ?";
        $params[] = $category;
    }
    
    if ($lowStock) {
        $whereConditions[] = "i.quantity_in_stock <= i.min_stock_level";
    }
    
    $whereClause = '';
    if (!empty($whereConditions)) {
        $whereClause = ' WHERE ' . implode(' AND ', $whereConditions);
    }

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM inventory i" . $whereClause;
    $countStmt = $db->prepare($countQuery);
    
    for ($i = 0; $i < count($params); $i++) {
        $countStmt->bindParam($i + 1, $params[$i]);
    }
    
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get inventory items
    if (isset($_GET['id'])) {
        $query = "SELECT i.*, c.name as category_name FROM inventory i 
                  LEFT JOIN categories c ON i.category_id = c.id 
                  WHERE i.id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($item) {
            echo json_encode($item);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Item not found"));
        }
    } else {
        $query = "SELECT i.*, c.name as category_name FROM inventory i 
                  LEFT JOIN categories c ON i.category_id = c.id" . 
                  $whereClause . " ORDER BY i.created_at DESC LIMIT ? OFFSET ?";
        
        $stmt = $db->prepare($query);
        
        $paramIndex = 1;
        for ($i = 0; $i < count($params); $i++) {
            $stmt->bindParam($paramIndex++, $params[$i]);
        }
        
        $stmt->bindParam($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindParam($paramIndex, $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "data" => $items,
            "total" => $total,
            "page" => $page,
            "limit" => $limit,
            "pages" => ceil($total / $limit)
        ));
    }
}

function handlePost($db, $data, $user) {
    if (!$data || !$data->name || !$data->selling_price) {
        http_response_code(400);
        echo json_encode(array("message" => "Name and selling price are required"));
        return;
    }

    // Generate product code
    $productCode = 'PROD' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    $query = "INSERT INTO inventory (product_code, name, description, category_id, sku, barcode, purchase_price, selling_price, quantity_in_stock, min_stock_level, max_stock_level, unit, supplier_name, supplier_contact) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $productCode);
    $stmt->bindParam(2, $data->name);
    $stmt->bindParam(3, $data->description ?? null);
    $stmt->bindParam(4, $data->category_id ?? null);
    $stmt->bindParam(5, $data->sku ?? null);
    $stmt->bindParam(6, $data->barcode ?? null);
    $stmt->bindParam(7, $data->purchase_price ?? 0);
    $stmt->bindParam(8, $data->selling_price);
    $stmt->bindParam(9, $data->quantity_in_stock ?? 0);
    $stmt->bindParam(10, $data->min_stock_level ?? 10);
    $stmt->bindParam(11, $data->max_stock_level ?? 1000);
    $stmt->bindParam(12, $data->unit ?? 'pcs');
    $stmt->bindParam(13, $data->supplier_name ?? null);
    $stmt->bindParam(14, $data->supplier_contact ?? null);

    if ($stmt->execute()) {
        $itemId = $db->lastInsertId();
        
        // Log stock movement if initial stock > 0
        if (isset($data->quantity_in_stock) && $data->quantity_in_stock > 0) {
            logStockMovement($db, $itemId, 'in', $data->quantity_in_stock, 'adjustment', null, 'Initial stock', $user['id']);
        }
        
        http_response_code(201);
        echo json_encode(array(
            "message" => "Item created successfully",
            "id" => $itemId,
            "product_code" => $productCode
        ));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Unable to create item"));
    }
}

function handlePut($db, $data, $user) {
    if (!$data || !$data->id) {
        http_response_code(400);
        echo json_encode(array("message" => "Item ID is required"));
        return;
    }

    // Get current stock quantity
    $currentQuery = "SELECT quantity_in_stock FROM inventory WHERE id = ?";
    $currentStmt = $db->prepare($currentQuery);
    $currentStmt->bindParam(1, $data->id);
    $currentStmt->execute();
    $currentItem = $currentStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$currentItem) {
        http_response_code(404);
        echo json_encode(array("message" => "Item not found"));
        return;
    }

    $query = "UPDATE inventory SET name = ?, description = ?, category_id = ?, sku = ?, barcode = ?, purchase_price = ?, selling_price = ?, quantity_in_stock = ?, min_stock_level = ?, max_stock_level = ?, unit = ?, supplier_name = ?, supplier_contact = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $data->name);
    $stmt->bindParam(2, $data->description);
    $stmt->bindParam(3, $data->category_id);
    $stmt->bindParam(4, $data->sku);
    $stmt->bindParam(5, $data->barcode);
    $stmt->bindParam(6, $data->purchase_price);
    $stmt->bindParam(7, $data->selling_price);
    $stmt->bindParam(8, $data->quantity_in_stock);
    $stmt->bindParam(9, $data->min_stock_level);
    $stmt->bindParam(10, $data->max_stock_level);
    $stmt->bindParam(11, $data->unit);
    $stmt->bindParam(12, $data->supplier_name);
    $stmt->bindParam(13, $data->supplier_contact);
    $stmt->bindParam(14, $data->id);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        // Log stock movement if quantity changed
        $stockDiff = $data->quantity_in_stock - $currentItem['quantity_in_stock'];
        if ($stockDiff != 0) {
            $movementType = $stockDiff > 0 ? 'in' : 'out';
            $quantity = abs($stockDiff);
            logStockMovement($db, $data->id, $movementType, $quantity, 'adjustment', null, 'Stock adjustment', $user['id']);
        }
        
        echo json_encode(array("message" => "Item updated successfully"));
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Item not found or no changes made"));
    }
}

function handleDelete($db) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Item ID is required"));
        return;
    }

    $query = "UPDATE inventory SET is_active = 0 WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $_GET['id']);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(array("message" => "Item deleted successfully"));
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Item not found"));
    }
}

function logStockMovement($db, $productId, $movementType, $quantity, $referenceType, $referenceId, $notes, $userId) {
    $query = "INSERT INTO stock_movements (product_id, movement_type, quantity, reference_type, reference_id, notes, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $productId);
    $stmt->bindParam(2, $movementType);
    $stmt->bindParam(3, $quantity);
    $stmt->bindParam(4, $referenceType);
    $stmt->bindParam(5, $referenceId);
    $stmt->bindParam(6, $notes);
    $stmt->bindParam(7, $userId);
    $stmt->execute();
}
?>