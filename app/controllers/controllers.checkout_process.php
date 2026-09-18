<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'app/models/Database.php';
require_once 'app/models/Cart.php';
require_once 'app/models/Order.php';
require_once 'app/models/lib.php';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

if (!isset($_SESSION['customer_loggedin']) || $_SESSION['customer_loggedin'] !== true) {
    header('Location: ' . $basePath . '/customer-login?redirect=checkout');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $basePath . '/checkout');
    exit;
}

if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
    header('Location: ' . $basePath . '/checkout?message=' . urlencode('Your session expired. Please try again.'));
    exit;
}

$fullAddress = trim($_POST['full_address'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$phoneNumber = trim($_POST['phone_number'] ?? '');

if ($fullAddress === '' || $city === '' || $state === '' || $phoneNumber === '') {
    header('Location: ' . $basePath . '/checkout?message=' . urlencode('Please fill in all delivery address fields.'));
    exit;
}

$customerId = (int) $_SESSION['customer_session']['id'];
$customerEmail = $_SESSION['customer_session']['email'];
$sessionId = session_id();

// Never trust a cart total from the browser — recalculate from the DB.
$cartItems = $CartModel->getCartItems($sessionId, $customerId);

if (empty($cartItems)) {
    header('Location: ' . $basePath . '/cart');
    exit;
}

$subtotal = 0;
foreach ($cartItems as $item) {
    $subtotal += $item['unit_price'] * $item['quantity'];
}
$deliveryFee = 0.00;
$total = $subtotal + $deliveryFee;

$addressData = [
    'full_address' => $fullAddress,
    'city' => $city,
    'state' => $state,
    'phone_number' => $phoneNumber,
];

try {
    $order = $OrderModel->createOrder($customerId, $addressData, $cartItems, $total);
} catch (Exception $e) {
    header('Location: ' . $basePath . '/checkout?message=' . urlencode('Could not create your order. Please try again.'));
    exit;
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$callbackUrl = $protocol . '://' . $host . $basePath . '/checkout/callback';

$amountInKobo = (int) round($total * 100);

$paystackPayload = json_encode([
    'email' => $customerEmail,
    'amount' => $amountInKobo,
    'callback_url' => $callbackUrl,
    'metadata' => [
        'order_id' => $order['id'],
        'order_number' => $order['order_number'],
    ],
]);

$ch = curl_init('https://api.paystack.co/transaction/initialize');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $paystackPayload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
    'Content-Type: application/json',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);

$response = curl_exec($ch);
$curlErrorMessage = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $curlErrorMessage !== '') {
    header('Location: ' . $basePath . '/checkout?message=' . urlencode('Could not reach the payment gateway. Please try again.'));
    exit;
}

$paystackResult = json_decode($response, true);

if ($httpCode !== 200 || empty($paystackResult['status']) || empty($paystackResult['data']['authorization_url'])) {
    header('Location: ' . $basePath . '/checkout?message=' . urlencode('Payment initialization failed. Please try again.'));
    exit;
}

header('Location: ' . $paystackResult['data']['authorization_url']);
exit;
