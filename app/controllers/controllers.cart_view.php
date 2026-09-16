<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'app/models/Database.php';
require_once 'app/models/Cart.php';

$customerId = null;
if (isset($_SESSION['customer_loggedin']) && $_SESSION['customer_loggedin'] === true) {
    $customerId = (int) $_SESSION['customer_session']['id'];
}

$sessionId = session_id();

$cartItems = $CartModel->getCartItems($sessionId, $customerId);

$cartTotal = 0;
foreach ($cartItems as &$item) {
    $item['line_subtotal'] = $item['unit_price'] * $item['quantity'];
    $cartTotal += $item['line_subtotal'];
}
unset($item);

require "app/views/views.cart.php";
