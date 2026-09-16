<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'app/models/Database.php';
require_once 'app/models/Cart.php';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

if (!isset($_SESSION['customer_loggedin']) || $_SESSION['customer_loggedin'] !== true) {
    $message = urlencode('Please log in to continue to checkout.');
    header('Location: ' . $basePath . '/customer-login?redirect=checkout&message=' . $message);
    exit;
}

$customerId = (int) $_SESSION['customer_session']['id'];
$sessionId = session_id();

$cartItems = $CartModel->getCartItems($sessionId, $customerId);

if (empty($cartItems)) {
    header('Location: ' . $basePath . '/cart');
    exit;
}

$cartTotal = 0;
foreach ($cartItems as &$item) {
    $item['line_subtotal'] = $item['unit_price'] * $item['quantity'];
    $cartTotal += $item['line_subtotal'];
}
unset($item);

require "app/views/views.checkout.php";
