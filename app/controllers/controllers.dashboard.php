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

// Stock threshold for the "needs attention" card. A plain named constant,
// not a schema field — see the approved plan for why.
const DASHBOARD_LOW_STOCK_THRESHOLD = 5;

$totalRevenue = $OrderModel->getTotalRevenue();
$totalOrders = $OrderModel->getTotalOrdersCount();
$totalProducts = $ProductModel->getProductCount();
$totalCustomers = $CustomerModel->getCustomerCount();

$bestsellers = $OrderModel->getBestsellers(10);
$recentOrders = array_slice($OrderModel->getAllOrders(), 0, 10);

$pendingOrdersCount = $OrderModel->getPendingOrdersCount();
$lowStockCount = $ProductModel->getLowStockCount(DASHBOARD_LOW_STOCK_THRESHOLD);
$outOfStockCount = $ProductModel->getOutOfStockCount();

// Trend badges: current 30 days vs. the prior 30 days. Fixed at 30 days
// regardless of the chart's own 7/30/90 toggle below — these are two
// separate, independently-scoped things.
$trendPeriodDays = 30;
$now = date('Y-m-d H:i:s');
$periodStart = date('Y-m-d H:i:s', strtotime("-{$trendPeriodDays} days"));
$previousPeriodStart = date('Y-m-d H:i:s', strtotime('-' . ($trendPeriodDays * 2) . ' days'));

/**
 * Percent-change trend for a metric with a real "previous period" to
 * compare against (Revenue, Orders). Guards the previous=0 case explicitly
 * rather than relying on a suppressed division-by-zero warning.
 */
function dashboardPercentTrend($current, $previous) {
    if ($previous == 0) {
        return $current > 0 ? ['type' => 'new'] : null;
    }

    $percent = (($current - $previous) / $previous) * 100;

    if (abs($percent) < 0.5) {
        return ['type' => 'flat', 'percent' => 0];
    }

    return ['type' => $percent > 0 ? 'up' : 'down', 'percent' => (int) round($percent)];
}

$currentRevenue = $OrderModel->getRevenueBetween($periodStart, $now);
$previousRevenue = $OrderModel->getRevenueBetween($previousPeriodStart, $periodStart);
$revenueTrend = dashboardPercentTrend($currentRevenue, $previousRevenue);

$currentOrdersInPeriod = $OrderModel->getOrdersCountBetween($periodStart, $now);
$previousOrdersInPeriod = $OrderModel->getOrdersCountBetween($previousPeriodStart, $periodStart);
$ordersTrend = dashboardPercentTrend($currentOrdersInPeriod, $previousOrdersInPeriod);

// Products/Customers use a plain "+N this period" count instead of a
// percentage — no division, so no zero-previous edge case to guard.
$newProductsCount = $ProductModel->getProductCountBetween($periodStart, $now);
$newCustomersCount = $CustomerModel->getCustomerCountBetween($periodStart, $now);

// Zero-fill every day in the range so the chart shows real slow days as
// zero points rather than silently skipping them. Default range matches
// the chart's default toggle state (30D); the 7D/90D options are fetched
// client-side from /dashboard/revenue-chart.
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

$pageTitle = ucfirst($_SESSION['user_session']['role']) . ' Dashboard';

require "app/views/views.dashboard.php";
