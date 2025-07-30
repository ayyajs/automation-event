<?php
require_once __DIR__ . '/../headers.php';
require_once __DIR__ . '/../config.php';

$date = $_GET['date'] ?? date('Y-m-d');

$stmt = $pdo->prepare('SELECT SUM(total) AS total_sales, COUNT(*) AS orders FROM sales WHERE DATE(date) = ?');
$stmt->execute([$date]);
$result = $stmt->fetch();

echo json_encode([
    'date' => $date,
    'total_sales' => $result['total_sales'] ?? 0,
    'orders' => $result['orders'] ?? 0,
]);
?>