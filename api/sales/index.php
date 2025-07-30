<?php
require_once __DIR__ . '/../headers.php';
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM sales WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query('SELECT * FROM sales');
            echo json_encode($stmt->fetchAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO sales (customer_id, date, total) VALUES (?, ?, ?)');
        $stmt->execute([$data['customer_id'], $data['date'], $data['total']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;
    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE sales SET customer_id = ?, date = ?, total = ? WHERE id = ?');
        $stmt->execute([$data['customer_id'], $data['date'], $data['total'], $id]);
        echo json_encode(['message' => 'Updated']);
        break;
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM sales WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Deleted']);
        break;
    default:
        http_response_code(405);
        break;
}
?>