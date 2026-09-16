<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once 'app/models/Database.php';
require_once 'app/models/Cart.php';

$cartItemId = filter_var($_POST['cart_item_id'] ?? null, FILTER_VALIDATE_INT);
if ($cartItemId === false || $cartItemId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid cart item.']);
    exit;
}

$customerId = null;
if (isset($_SESSION['customer_loggedin']) && $_SESSION['customer_loggedin'] === true) {
    $customerId = (int) $_SESSION['customer_session']['id'];
}

$sessionId = session_id();

$removed = $CartModel->removeItem($cartItemId, $sessionId, $customerId);

if ($removed) {
    echo json_encode(['success' => true, 'message' => 'Item removed.']);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Cart item not found.']);
}
