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
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    $offset = ($page - 1) * $limit;

    // Build where conditions
    $whereConditions = array();
    $params = array();
    
    if ($search) {
        $whereConditions[] = "(s.sale_number LIKE ? OR c.first_name LIKE ? OR c.last_name LIKE ?)";
        $searchParam = "%{$search}%";
        $params[] = $searchParam;
        $params[] = $searchParam;
        $params[] = $searchParam;
    }
    
    if ($dateFrom) {
        $whereConditions[] = "DATE(s.sale_date) >= ?";
        $params[] = $dateFrom;
    }
    
    if ($dateTo) {
        $whereConditions[] = "DATE(s.sale_date) <= ?";
        $params[] = $dateTo;
    }
    
    $whereClause = '';
    if (!empty($whereConditions)) {
        $whereClause = ' WHERE ' . implode(' AND ', $whereConditions);
    }

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM sales s 
                   LEFT JOIN customers c ON s.customer_id = c.id" . $whereClause;
    $countStmt = $db->prepare($countQuery);
    
    for ($i = 0; $i < count($params); $i++) {
        $countStmt->bindParam($i + 1, $params[$i]);
    }
    
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get sales
    if (isset($_GET['id'])) {
        $query = "SELECT s.*, 
                         CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                         CONCAT(u.first_name, ' ', u.last_name) as user_name
                  FROM sales s 
                  LEFT JOIN customers c ON s.customer_id = c.id 
                  LEFT JOIN users u ON s.user_id = u.id
                  WHERE s.id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($sale) {
            // Get sale items
            $itemsQuery = "SELECT si.*, i.name as product_name, i.product_code 
                          FROM sale_items si 
                          LEFT JOIN inventory i ON si.product_id = i.id 
                          WHERE si.sale_id = ?";
            $itemsStmt = $db->prepare($itemsQuery);
            $itemsStmt->bindParam(1, $sale['id']);
            $itemsStmt->execute();
            $sale['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode($sale);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Sale not found"));
        }
    } else {
        $query = "SELECT s.*, 
                         CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                         CONCAT(u.first_name, ' ', u.last_name) as user_name
                  FROM sales s 
                  LEFT JOIN customers c ON s.customer_id = c.id 
                  LEFT JOIN users u ON s.user_id = u.id" . 
                  $whereClause . " ORDER BY s.sale_date DESC LIMIT ? OFFSET ?";
        
        $stmt = $db->prepare($query);
        
        $paramIndex = 1;
        for ($i = 0; $i < count($params); $i++) {
            $stmt->bindParam($paramIndex++, $params[$i]);
        }
        
        $stmt->bindParam($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindParam($paramIndex, $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "data" => $sales,
            "total" => $total,
            "page" => $page,
            "limit" => $limit,
            "pages" => ceil($total / $limit)
        ));
    }
}

function handlePost($db, $data, $user) {
    if (!$data || !$data->items || empty($data->items)) {
        http_response_code(400);
        echo json_encode(array("message" => "Sale items are required"));
        return;
    }

    try {
        $db->beginTransaction();

        // Generate sale number
        $saleNumber = 'SAL-' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Calculate totals
        $subtotal = 0;
        foreach ($data->items as $item) {
            $lineTotal = $item->quantity * $item->unit_price;
            $discount = $lineTotal * ($item->discount_percentage ?? 0) / 100;
            $subtotal += $lineTotal - $discount;
        }
        
        $taxAmount = $subtotal * ($data->tax_rate ?? 0) / 100;
        $discountAmount = $subtotal * ($data->discount_percentage ?? 0) / 100;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        // Insert sale
        $query = "INSERT INTO sales (sale_number, customer_id, user_id, subtotal, tax_amount, discount_amount, total_amount, payment_method, payment_status, notes) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $saleNumber);
        $stmt->bindParam(2, $data->customer_id ?? null);
        $stmt->bindParam(3, $user['id']);
        $stmt->bindParam(4, $subtotal);
        $stmt->bindParam(5, $taxAmount);
        $stmt->bindParam(6, $discountAmount);
        $stmt->bindParam(7, $totalAmount);
        $stmt->bindParam(8, $data->payment_method ?? 'cash');
        $stmt->bindParam(9, $data->payment_status ?? 'paid');
        $stmt->bindParam(10, $data->notes ?? null);
        
        $stmt->execute();
        $saleId = $db->lastInsertId();

        // Insert sale items and update inventory
        foreach ($data->items as $item) {
            $lineTotal = $item->quantity * $item->unit_price;
            $discount = $lineTotal * ($item->discount_percentage ?? 0) / 100;
            $finalLineTotal = $lineTotal - $discount;
            
            // Insert sale item
            $itemQuery = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, discount_percentage, line_total) 
                         VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $db->prepare($itemQuery);
            $itemStmt->bindParam(1, $saleId);
            $itemStmt->bindParam(2, $item->product_id);
            $itemStmt->bindParam(3, $item->quantity);
            $itemStmt->bindParam(4, $item->unit_price);
            $itemStmt->bindParam(5, $item->discount_percentage ?? 0);
            $itemStmt->bindParam(6, $finalLineTotal);
            $itemStmt->execute();
            
            // Update inventory
            $inventoryQuery = "UPDATE inventory SET quantity_in_stock = quantity_in_stock - ? WHERE id = ?";
            $inventoryStmt = $db->prepare($inventoryQuery);
            $inventoryStmt->bindParam(1, $item->quantity);
            $inventoryStmt->bindParam(2, $item->product_id);
            $inventoryStmt->execute();
            
            // Log stock movement
            logStockMovement($db, $item->product_id, 'out', $item->quantity, 'sale', $saleId, 'Sale transaction', $user['id']);
        }

        $db->commit();
        
        http_response_code(201);
        echo json_encode(array(
            "message" => "Sale created successfully",
            "id" => $saleId,
            "sale_number" => $saleNumber,
            "total_amount" => $totalAmount
        ));
        
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(500);
        echo json_encode(array("message" => "Unable to create sale: " . $e->getMessage()));
    }
}

function handlePut($db, $data, $user) {
    if (!$data || !$data->id) {
        http_response_code(400);
        echo json_encode(array("message" => "Sale ID is required"));
        return;
    }

    try {
        $db->beginTransaction();

        // Get current sale items to reverse inventory changes
        $currentItemsQuery = "SELECT product_id, quantity FROM sale_items WHERE sale_id = ?";
        $currentItemsStmt = $db->prepare($currentItemsQuery);
        $currentItemsStmt->bindParam(1, $data->id);
        $currentItemsStmt->execute();
        $currentItems = $currentItemsStmt->fetchAll(PDO::FETCH_ASSOC);

        // Reverse inventory changes
        foreach ($currentItems as $currentItem) {
            $inventoryQuery = "UPDATE inventory SET quantity_in_stock = quantity_in_stock + ? WHERE id = ?";
            $inventoryStmt = $db->prepare($inventoryQuery);
            $inventoryStmt->bindParam(1, $currentItem['quantity']);
            $inventoryStmt->bindParam(2, $currentItem['product_id']);
            $inventoryStmt->execute();
        }

        // Delete current sale items
        $deleteItemsQuery = "DELETE FROM sale_items WHERE sale_id = ?";
        $deleteItemsStmt = $db->prepare($deleteItemsQuery);
        $deleteItemsStmt->bindParam(1, $data->id);
        $deleteItemsStmt->execute();

        // Recalculate totals
        $subtotal = 0;
        foreach ($data->items as $item) {
            $lineTotal = $item->quantity * $item->unit_price;
            $discount = $lineTotal * ($item->discount_percentage ?? 0) / 100;
            $subtotal += $lineTotal - $discount;
        }
        
        $taxAmount = $subtotal * ($data->tax_rate ?? 0) / 100;
        $discountAmount = $subtotal * ($data->discount_percentage ?? 0) / 100;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        // Update sale
        $query = "UPDATE sales SET customer_id = ?, subtotal = ?, tax_amount = ?, discount_amount = ?, total_amount = ?, payment_method = ?, payment_status = ?, notes = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $data->customer_id);
        $stmt->bindParam(2, $subtotal);
        $stmt->bindParam(3, $taxAmount);
        $stmt->bindParam(4, $discountAmount);
        $stmt->bindParam(5, $totalAmount);
        $stmt->bindParam(6, $data->payment_method);
        $stmt->bindParam(7, $data->payment_status);
        $stmt->bindParam(8, $data->notes);
        $stmt->bindParam(9, $data->id);
        $stmt->execute();

        // Insert new sale items and update inventory
        foreach ($data->items as $item) {
            $lineTotal = $item->quantity * $item->unit_price;
            $discount = $lineTotal * ($item->discount_percentage ?? 0) / 100;
            $finalLineTotal = $lineTotal - $discount;
            
            // Insert sale item
            $itemQuery = "INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, discount_percentage, line_total) 
                         VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $db->prepare($itemQuery);
            $itemStmt->bindParam(1, $data->id);
            $itemStmt->bindParam(2, $item->product_id);
            $itemStmt->bindParam(3, $item->quantity);
            $itemStmt->bindParam(4, $item->unit_price);
            $itemStmt->bindParam(5, $item->discount_percentage ?? 0);
            $itemStmt->bindParam(6, $finalLineTotal);
            $itemStmt->execute();
            
            // Update inventory
            $inventoryQuery = "UPDATE inventory SET quantity_in_stock = quantity_in_stock - ? WHERE id = ?";
            $inventoryStmt = $db->prepare($inventoryQuery);
            $inventoryStmt->bindParam(1, $item->quantity);
            $inventoryStmt->bindParam(2, $item->product_id);
            $inventoryStmt->execute();
            
            // Log stock movement
            logStockMovement($db, $item->product_id, 'out', $item->quantity, 'sale', $data->id, 'Sale update', $user['id']);
        }

        $db->commit();
        echo json_encode(array("message" => "Sale updated successfully"));
        
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(500);
        echo json_encode(array("message" => "Unable to update sale: " . $e->getMessage()));
    }
}

function handleDelete($db) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Sale ID is required"));
        return;
    }

    try {
        $db->beginTransaction();

        // Get sale items to reverse inventory changes
        $itemsQuery = "SELECT product_id, quantity FROM sale_items WHERE sale_id = ?";
        $itemsStmt = $db->prepare($itemsQuery);
        $itemsStmt->bindParam(1, $_GET['id']);
        $itemsStmt->execute();
        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        // Reverse inventory changes
        foreach ($items as $item) {
            $inventoryQuery = "UPDATE inventory SET quantity_in_stock = quantity_in_stock + ? WHERE id = ?";
            $inventoryStmt = $db->prepare($inventoryQuery);
            $inventoryStmt->bindParam(1, $item['quantity']);
            $inventoryStmt->bindParam(2, $item['product_id']);
            $inventoryStmt->execute();
        }

        // Delete sale items
        $deleteItemsQuery = "DELETE FROM sale_items WHERE sale_id = ?";
        $deleteItemsStmt = $db->prepare($deleteItemsQuery);
        $deleteItemsStmt->bindParam(1, $_GET['id']);
        $deleteItemsStmt->execute();

        // Delete sale
        $query = "DELETE FROM sales WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $_GET['id']);
        
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            $db->commit();
            echo json_encode(array("message" => "Sale deleted successfully"));
        } else {
            $db->rollback();
            http_response_code(404);
            echo json_encode(array("message" => "Sale not found"));
        }
        
    } catch (Exception $e) {
        $db->rollback();
        http_response_code(500);
        echo json_encode(array("message" => "Unable to delete sale: " . $e->getMessage()));
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