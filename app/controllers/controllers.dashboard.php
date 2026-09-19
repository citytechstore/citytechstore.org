<?php
require_once 'app/models/lib.php';

requireRole(['admin', 'worker']);

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    header('Location: ' . $basePath . '/login');
    exit();
}

require_once 'app/models/Database.php';
require_once 'app/models/Order.php';
require_once 'app/models/Product.php';
require_once 'app/models/Customer.php';

$totalRevenue = $OrderModel->getTotalRevenue();
$totalOrders = $OrderModel->getTotalOrdersCount();
$totalProducts = $ProductModel->getProductCount();
$totalCustomers = $CustomerModel->getCustomerCount();

$bestsellers = $OrderModel->getBestsellers(10);
$recentOrders = array_slice($OrderModel->getAllOrders(), 0, 10);

// Zero-fill every day in the range so the chart shows real slow days as
// zero points rather than silently skipping them.
$revenueDays = 30;
$dailyRevenueRows = $OrderModel->getDailyRevenueForLastNDays($revenueDays);
$revenueByDay = [];
foreach ($dailyRevenueRows as $row) {
    $revenueByDay[$row['day']] = (float) $row['revenue'];
}

$chartLabels = [];
$chartData = [];
for ($i = $revenueDays - 1; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-{$i} days"));
    $chartLabels[] = date('M j', strtotime($day));
    $chartData[] = $revenueByDay[$day] ?? 0;
}

require "app/views/views.dashboard.php";
