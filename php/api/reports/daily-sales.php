<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
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

$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-d');
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');

// Daily sales summary
$summaryQuery = "SELECT 
    DATE(sale_date) as sale_date,
    COUNT(*) as total_transactions,
    SUM(total_amount) as total_sales,
    AVG(total_amount) as average_sale,
    SUM(tax_amount) as total_tax,
    SUM(discount_amount) as total_discount
FROM sales 
WHERE DATE(sale_date) BETWEEN ? AND ?
GROUP BY DATE(sale_date)
ORDER BY sale_date DESC";

$summaryStmt = $db->prepare($summaryQuery);
$summaryStmt->bindParam(1, $dateFrom);
$summaryStmt->bindParam(2, $dateTo);
$summaryStmt->execute();
$dailySummary = $summaryStmt->fetchAll(PDO::FETCH_ASSOC);

// Top selling products
$topProductsQuery = "SELECT 
    i.name as product_name,
    i.product_code,
    SUM(si.quantity) as total_quantity,
    SUM(si.line_total) as total_revenue,
    COUNT(DISTINCT s.id) as transaction_count
FROM sale_items si
JOIN inventory i ON si.product_id = i.id
JOIN sales s ON si.sale_id = s.id
WHERE DATE(s.sale_date) BETWEEN ? AND ?
GROUP BY si.product_id
ORDER BY total_revenue DESC
LIMIT 10";

$topProductsStmt = $db->prepare($topProductsQuery);
$topProductsStmt->bindParam(1, $dateFrom);
$topProductsStmt->bindParam(2, $dateTo);
$topProductsStmt->execute();
$topProducts = $topProductsStmt->fetchAll(PDO::FETCH_ASSOC);

// Sales by payment method
$paymentMethodQuery = "SELECT 
    payment_method,
    COUNT(*) as transaction_count,
    SUM(total_amount) as total_amount
FROM sales 
WHERE DATE(sale_date) BETWEEN ? AND ?
GROUP BY payment_method
ORDER BY total_amount DESC";

$paymentMethodStmt = $db->prepare($paymentMethodQuery);
$paymentMethodStmt->bindParam(1, $dateFrom);
$paymentMethodStmt->bindParam(2, $dateTo);
$paymentMethodStmt->execute();
$paymentMethods = $paymentMethodStmt->fetchAll(PDO::FETCH_ASSOC);

// Hourly sales distribution
$hourlySalesQuery = "SELECT 
    HOUR(sale_date) as hour,
    COUNT(*) as transaction_count,
    SUM(total_amount) as total_amount
FROM sales 
WHERE DATE(sale_date) BETWEEN ? AND ?
GROUP BY HOUR(sale_date)
ORDER BY hour";

$hourlySalesStmt = $db->prepare($hourlySalesQuery);
$hourlySalesStmt->bindParam(1, $dateFrom);
$hourlySalesStmt->bindParam(2, $dateTo);
$hourlySalesStmt->execute();
$hourlySales = $hourlySalesStmt->fetchAll(PDO::FETCH_ASSOC);

// Overall totals
$totalsQuery = "SELECT 
    COUNT(*) as total_transactions,
    SUM(total_amount) as total_revenue,
    AVG(total_amount) as average_transaction,
    SUM(tax_amount) as total_tax,
    SUM(discount_amount) as total_discount,
    COUNT(DISTINCT customer_id) as unique_customers
FROM sales 
WHERE DATE(sale_date) BETWEEN ? AND ?";

$totalsStmt = $db->prepare($totalsQuery);
$totalsStmt->bindParam(1, $dateFrom);
$totalsStmt->bindParam(2, $dateTo);
$totalsStmt->execute();
$totals = $totalsStmt->fetch(PDO::FETCH_ASSOC);

// Low stock alerts
$lowStockQuery = "SELECT 
    product_code,
    name,
    quantity_in_stock,
    min_stock_level,
    (min_stock_level - quantity_in_stock) as shortage
FROM inventory 
WHERE quantity_in_stock <= min_stock_level AND is_active = 1
ORDER BY shortage DESC
LIMIT 10";

$lowStockStmt = $db->prepare($lowStockQuery);
$lowStockStmt->execute();
$lowStock = $lowStockStmt->fetchAll(PDO::FETCH_ASSOC);

// Recent transactions
$recentTransactionsQuery = "SELECT 
    s.id,
    s.sale_number,
    s.sale_date,
    s.total_amount,
    s.payment_method,
    CONCAT(c.first_name, ' ', c.last_name) as customer_name
FROM sales s
LEFT JOIN customers c ON s.customer_id = c.id
WHERE DATE(s.sale_date) BETWEEN ? AND ?
ORDER BY s.sale_date DESC
LIMIT 10";

$recentTransactionsStmt = $db->prepare($recentTransactionsQuery);
$recentTransactionsStmt->bindParam(1, $dateFrom);
$recentTransactionsStmt->bindParam(2, $dateTo);
$recentTransactionsStmt->execute();
$recentTransactions = $recentTransactionsStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array(
    "date_range" => array(
        "from" => $dateFrom,
        "to" => $dateTo
    ),
    "summary" => $totals,
    "daily_breakdown" => $dailySummary,
    "top_products" => $topProducts,
    "payment_methods" => $paymentMethods,
    "hourly_sales" => $hourlySales,
    "low_stock_alerts" => $lowStock,
    "recent_transactions" => $recentTransactions
));
?>