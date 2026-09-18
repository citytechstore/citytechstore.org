<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'app/models/Database.php';
require_once 'app/models/Order.php';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

if (empty($_SESSION['customer_loggedin']) || empty($_SESSION['customer_session']['id'])) {
    header('Location: ' . $basePath . '/customer-login?redirect=my-orders');
    exit;
}

$customerId = (int) $_SESSION['customer_session']['id'];

$order = null;
$orderItems = [];

if (isset($_GET['id'])) {
    $candidate = $OrderModel->getOrderWithDetails((int) $_GET['id']);

    // Same outcome for "no such order" and "exists but belongs to someone
    // else" — never reveal which one it is.
    if ($candidate && (int) $candidate['customer_id'] === $customerId) {
        $order = $candidate;
        $orderItems = $OrderModel->getOrderItems($order['id']);
    }
}

$orders = $OrderModel->getOrdersByCustomerId($customerId);

require "app/views/views.customer_orders.php";
