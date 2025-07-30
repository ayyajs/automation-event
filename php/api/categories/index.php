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
    if (isset($_GET['id'])) {
        $query = "SELECT * FROM categories WHERE id = ? AND is_active = 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $_GET['id']);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Category not found"));
        }
    } else {
        $query = "SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categories);
    }
}

function handlePost($db, $data) {
    if (!$data || !$data->name) {
        http_response_code(400);
        echo json_encode(array("message" => "Category name is required"));
        return;
    }

    $query = "INSERT INTO categories (name, description) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $data->name);
    $stmt->bindParam(2, $data->description ?? null);

    if ($stmt->execute()) {
        $categoryId = $db->lastInsertId();
        http_response_code(201);
        echo json_encode(array(
            "message" => "Category created successfully",
            "id" => $categoryId
        ));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Unable to create category"));
    }
}

function handlePut($db, $data) {
    if (!$data || !$data->id || !$data->name) {
        http_response_code(400);
        echo json_encode(array("message" => "Category ID and name are required"));
        return;
    }

    $query = "UPDATE categories SET name = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND is_active = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $data->name);
    $stmt->bindParam(2, $data->description);
    $stmt->bindParam(3, $data->id);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(array("message" => "Category updated successfully"));
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Category not found or no changes made"));
    }
}

function handleDelete($db) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Category ID is required"));
        return;
    }

    $query = "UPDATE categories SET is_active = 0 WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $_GET['id']);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        echo json_encode(array("message" => "Category deleted successfully"));
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Category not found"));
    }
}
?>