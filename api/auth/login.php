<?php
require_once __DIR__ . '/../headers.php';
require_once __DIR__ . '/../config.php';

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? null;
$password = $input['password'] ?? null;

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['error' => 'Username and password required']);
    exit;
}

// TODO: Validate username/password against users table (not implemented)
$dummyToken = base64_encode(random_bytes(16));

http_response_code(200);
echo json_encode([
    'token' => $dummyToken,
    'user' => [
        'id' => 1,
        'username' => $username,
    ],
]);
?>