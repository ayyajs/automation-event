<?php
require_once __DIR__ . '/../headers.php';
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query('SELECT `key`, `value` FROM settings');
        $rows = $stmt->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }
        echo json_encode($settings);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        foreach ($data as $key => $value) {
            $stmt = $pdo->prepare('REPLACE INTO settings (`key`, `value`) VALUES (?, ?)');
            $stmt->execute([$key, $value]);
        }
        echo json_encode(['message' => 'Settings updated']);
        break;
    default:
        http_response_code(405);
        break;
}
?>