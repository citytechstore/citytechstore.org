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
require_once 'app/models/Product.php';
require_once 'app/models/Cart.php';

$productId = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);
if ($productId === false || $productId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product.']);
    exit;
}

$quantity = filter_var($_POST['quantity'] ?? '1', FILTER_VALIDATE_INT);
if ($quantity === false || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Quantity must be a positive integer.']);
    exit;
}

$product = $ProductModel->getProductById($productId);
if (!$product) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Product not found.']);
    exit;
}

$customerId = null;
if (isset($_SESSION['customer_loggedin']) && $_SESSION['customer_loggedin'] === true) {
    $customerId = (int) $_SESSION['customer_session']['id'];
}

$sessionId = session_id();

try {
    $added = $CartModel->addItem($sessionId, $customerId, $productId, $quantity);

    if ($added) {
        echo json_encode(['success' => true, 'message' => 'Added to cart.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to add item to cart.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
}
