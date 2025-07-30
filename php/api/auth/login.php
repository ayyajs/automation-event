<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../config/jwt.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
    $query = "SELECT id, username, email, password, first_name, last_name, role FROM users WHERE username = ? AND is_active = 1 LIMIT 0,1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $data->username);
    $stmt->execute();
    $num = $stmt->rowCount();

    if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($data->password, $row['password'])) {
            $jwt = new JWTHandler();
            $token = $jwt->generateToken($row);

            http_response_code(200);
            echo json_encode(array(
                "message" => "Successful login.",
                "token" => $token,
                "user" => array(
                    "id" => $row['id'],
                    "username" => $row['username'],
                    "email" => $row['email'],
                    "first_name" => $row['first_name'],
                    "last_name" => $row['last_name'],
                    "role" => $row['role']
                ),
                "expires_in" => 24 * 60 * 60 // 24 hours in seconds
            ));
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid credentials."));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Invalid credentials."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Username and password are required."));
}
?>