<?php
require_once __DIR__ . '/../headers.php';
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM invoices WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query('SELECT * FROM invoices');
            echo json_encode($stmt->fetchAll());
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO invoices (sale_id, date, amount) VALUES (?, ?, ?)');
        $stmt->execute([$data['sale_id'], $data['date'], $data['amount']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;
    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
            exit;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE invoices SET sale_id = ?, date = ?, amount = ? WHERE id = ?');
        $stmt->execute([$data['sale_id'], $data['date'], $data['amount'], $id]);
        echo json_encode(['message' => 'Updated']);
        break;
    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID required']);
            exit;
        }
        $stmt = $pdo->prepare('DELETE FROM invoices WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Deleted']);
        break;
    default:
        http_response_code(405);
        break;
}
?>