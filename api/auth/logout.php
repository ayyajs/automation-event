<?php
require_once __DIR__ . '/../headers.php';

http_response_code(200);
echo json_encode(['message' => 'Logged out']);
?>