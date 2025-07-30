<?php
require_once __DIR__ . '/../headers.php';
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ?');
            $stmt->execute([$id]);
            $customer = $stmt->fetch();
            echo json_encode($customer);
        } else {
            $stmt = $pdo->query('SELECT * FROM customers');
            echo json_encode($stmt->fetchAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)');
        $stmt->execute([$data['name'], $data['email'], $data['phone']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;
    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?');
        $stmt->execute([$data['name'], $data['email'], $data['phone'], $id]);
        echo json_encode(['message' => 'Updated']);
        break;
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM customers WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Deleted']);
        break;
    default:
        http_response_code(405);
        break;
}
?>