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
        handlePost($db, $data);
        break;
    case 'PUT':
        handlePut($db, $data);
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
    $offset = ($page - 1) * $limit;

    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM customers";
    if ($search) {
        $countQuery .= " WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR customer_code LIKE ?";
    }
    $countStmt = $db->prepare($countQuery);
    
    if ($search) {
        $searchParam = "%{$search}%";
        $countStmt->bindParam(1, $searchParam);
        $countStmt->bindParam(2, $searchParam);
        $countStmt->bindParam(3, $searchParam);
        $countStmt->bindParam(4, $searchParam);
    }
    
    $countStmt->execute();
    $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Get customers
    if (isset($_GET['id'])) {
        $query = "SELECT * FROM customers WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($customer) {
            echo json_encode($customer);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Customer not found"));
        }
    } else {
        $query = "SELECT * FROM customers";
        if ($search) {
            $query .= " WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR customer_code LIKE ?";
        }
        $query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        
        $stmt = $db->prepare($query);
        
        $paramIndex = 1;
        if ($search) {
            $searchParam = "%{$search}%";
            $stmt->bindParam($paramIndex++, $searchParam);
            $stmt->bindParam($paramIndex++, $searchParam);
            $stmt->bindParam($paramIndex++, $searchParam);
            $stmt->bindParam($paramIndex++, $searchParam);
        }
        
        $stmt->bindParam($paramIndex++, $limit, PDO::PARAM_INT);
        $stmt->bindParam($paramIndex, $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(array(
            "data" => $customers,
            "total" => $total,
            "page" => $page,
            "limit" => $limit,
            "pages" => ceil($total / $limit)
        ));
    }
}

function handlePost($db, $data) {
    if (!$data || !$data->first_name || !$data->last_name) {
        http_response_code(400);
        echo json_encode(array("message" => "First name and last name are required"));
        return;
    }

    // Generate customer code
    $customerCode = 'CUST' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    
    $query = "INSERT INTO customers (customer_code, first_name, last_name, email, phone, address, city, state, postal_code, country, date_of_birth) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $customerCode);
    $stmt->bindParam(2, $data->first_name);
    $stmt->bindParam(3, $data->last_name);
    $stmt->bindParam(4, $data->email ?? null);
    $stmt->bindParam(5, $data->phone ?? null);
    $stmt->bindParam(6, $data->address ?? null);
    $stmt->bindParam(7, $data->city ?? null);
    $stmt->bindParam(8, $data->state ?? null);
    $stmt->bindParam(9, $data->postal_code ?? null);
    $stmt->bindParam(10, $data->country ?? 'USA');
    $stmt->bindParam(11, $data->date_of_birth ?? null);

    if ($stmt->execute()) {
        $customerId = $db->lastInsertId();
        http_response_code(201);
        echo json_encode(array(
            "message" => "Customer created successfully",
            "id" => $customerId,
            "customer_code" => $customerCode
        ));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Unable to create customer"));
    }
}

function handlePut($db, $data) {
    if (!$data || !$data->id) {
        http_response_code(400);
        echo json_encode(array("message" => "Customer ID is required"));
        return;
    }

    $query = "UPDATE customers SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, city = ?, state = ?, postal_code = ?, country = ?, date_of_birth = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $data->first_name);
    $stmt->bindParam(2, $data->last_name);
    $stmt->bindParam(3, $data->email);
    $stmt->bindParam(4, $data->phone);
    $stmt->bindParam(5, $data->address);
    $stmt->bindParam(6, $data->city);
    $stmt->bindParam(7, $data->state);
    $stmt->bindParam(8, $data->postal_code);
    $stmt->bindParam(9, $data->country);
    $stmt->bindParam(10, $data->date_of_birth);
    $stmt->bindParam(11, $data->id);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(array("message" => "Customer updated successfully"));
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Customer not found or no changes made"));
    }
}

function handleDelete($db) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Customer ID is required"));
        return;
    }

    $query = "UPDATE customers SET is_active = 0 WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $_GET['id']);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(array("message" => "Customer deleted successfully"));
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Customer not found"));
    }
}
?>