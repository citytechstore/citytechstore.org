<?php
require_once 'app/models/lib.php';

requireRole(['admin', 'worker']);

require_once 'app/models/Database.php';
require_once 'app/models/Customer.php';
require_once 'app/models/Order.php';

// Safe against multi-byte/empty names and emoji — mb_substr never warns on
// a short/empty string, and htmlspecialchars() at output time neutralizes
// anything that would otherwise render oddly.
function customerInitials($firstName, $lastName)
{
    $first = trim((string) $firstName);
    $last = trim((string) $lastName);

    $initials = '';
    if ($first !== '') {
        $initials .= mb_substr($first, 0, 1);
    }
    if ($last !== '') {
        $initials .= mb_substr($last, 0, 1);
    }
    if ($initials === '') {
        $initials = '?';
    }

    return mb_strtoupper($initials);
}

$statusBadgeClass = [
    'pending' => 'bg-secondary',
    'confirmed' => 'bg-info text-dark',
    'shipped' => 'bg-primary',
    'delivered' => 'bg-success',
    'cancelled' => 'bg-danger',
];
$paymentBadgeClass = [
    'pending' => 'bg-secondary',
    'paid' => 'bg-success',
    'failed' => 'bg-danger',
];

$selectedCustomer = null;
$customerNotFound = false;
$primaryAddress = null;
$customerOrders = [];
$customerOrderItemsByOrderId = [];
$chartLabels = [];
$chartData = [];
$customerStats = [
    'total_spent' => 0.0,
    'orders_count' => 0,
    'paid_orders_count' => 0,
    'avg_order_value' => 0.0,
    'first_order_date' => null,
    'latest_order_date' => null,
];

if (isset($_GET['id']) && $_GET['id'] !== '') {
    $requestedId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($requestedId !== false && $requestedId > 0) {
        $selectedCustomer = $CustomerModel->getPublicProfileById((int) $requestedId);
    }

    if ($selectedCustomer) {
        $selectedCustomerId = (int) $selectedCustomer['id'];
        $primaryAddress = $CustomerModel->getPrimaryAddress($selectedCustomerId);
        $customerOrders = $OrderModel->getOrdersWithAddressByCustomerId($selectedCustomerId);

        foreach ($customerOrders as $order) {
            $customerStats['orders_count']++;
            if ($order['payment_status'] === 'paid') {
                $customerStats['paid_orders_count']++;
                $customerStats['total_spent'] += (float) $order['total'];
            }
            if ($customerStats['first_order_date'] === null || $order['created_at'] < $customerStats['first_order_date']) {
                $customerStats['first_order_date'] = $order['created_at'];
            }
            if ($customerStats['latest_order_date'] === null || $order['created_at'] > $customerStats['latest_order_date']) {
                $customerStats['latest_order_date'] = $order['created_at'];
            }
        }
        $customerStats['avg_order_value'] = $customerStats['paid_orders_count'] > 0
            ? ($customerStats['total_spent'] / $customerStats['paid_orders_count'])
            : 0.0;

        $customerOrderIds = array_column($customerOrders, 'id');
        $customerOrderItemsByOrderId = $OrderModel->getOrderItemsForOrderIds($customerOrderIds);

        // Zero-filled 12-month paid spend, same pattern as the dashboard's
        // daily revenue chart — real zero months render as real zero
        // points instead of being silently skipped.
        $monthlySpendRows = $OrderModel->getMonthlySpendForCustomer($selectedCustomerId, 12);
        $spendByMonth = [];
        foreach ($monthlySpendRows as $row) {
            $spendByMonth[$row['month']] = (float) $row['spend'];
        }
        for ($i = 11; $i >= 0; $i--) {
            $monthKey = date('Y-m', strtotime("-{$i} months"));
            $chartLabels[] = date('M Y', strtotime($monthKey . '-01'));
            $chartData[] = $spendByMonth[$monthKey] ?? 0;
        }
    } else {
        $customerNotFound = true;
    }
}

// List section — always loaded, one aggregate query, no per-row queries.
$customerListRows = $CustomerModel->getCustomerListWithStats();
$totalCustomersCount = $CustomerModel->getCustomerCount();
$newCustomersCount = $CustomerModel->getCustomerCountBetween(date('Y-m-d H:i:s', strtotime('-30 days')), date('Y-m-d H:i:s'));

$customersWithOrdersCount = 0;
$customersWithPaidOrdersCount = 0;
foreach ($customerListRows as $row) {
    if ((int) $row['orders_count'] > 0) {
        $customersWithOrdersCount++;
    }
    if ((float) $row['total_spent'] > 0) {
        $customersWithPaidOrdersCount++;
    }
}

$pageTitle = 'Customers';

require "app/views/views.customers.php";
