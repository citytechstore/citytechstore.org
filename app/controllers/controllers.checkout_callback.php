<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'app/models/Database.php';
require_once 'app/models/Cart.php';
require_once 'app/models/Order.php';

$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

$reference = trim($_GET['reference'] ?? '');

if ($reference === '') {
    header('Location: ' . $basePath . '/cart?message=' . urlencode('Missing payment reference.'));
    exit;
}

$paymentSuccessful = false;
$order = null;
$orderItems = [];
$failureMessage = 'Payment was not successful.';

// DB-first idempotency check: payment_reference is only ever written by
// markAsPaid(), so an order already 'paid' under this exact reference proves
// it was verified before — skip Paystack entirely, no network call needed.
$alreadyPaidOrder = $OrderModel->getOrderByPaymentReference($reference);

if ($alreadyPaidOrder && $alreadyPaidOrder['payment_status'] === 'paid') {
    $order = $alreadyPaidOrder;
    $orderItems = $OrderModel->getOrderItems($order['id']);
    $paymentSuccessful = true;
} else {
    $ch = curl_init('https://api.paystack.co/transaction/verify/' . rawurlencode($reference));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $curlErrorMessage = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $curlErrorMessage !== '') {
        $failureMessage = 'We could not confirm your payment right now. Please contact support with your order reference.';
        error_log('Paystack verify cURL error for reference ' . $reference . ': ' . $curlErrorMessage);
    } else {
        $paystackResult = json_decode($response, true);

        if ($httpCode !== 200 || empty($paystackResult['status']) || !isset($paystackResult['data']['status'])) {
            $failureMessage = 'We could not confirm your payment right now. Please contact support with your order reference.';
            error_log('Paystack verify unexpected response for reference ' . $reference . ': ' . $response);
        } else {
            $orderId = $paystackResult['data']['metadata']['order_id'] ?? null;
            $order = $orderId ? $OrderModel->getOrderById((int) $orderId) : null;

            if (!$order) {
                $failureMessage = 'We could not find the order for this payment. Please contact support with your order reference.';
                error_log('Paystack verify: no matching order for reference ' . $reference . ', order_id metadata: ' . var_export($orderId, true));
            } elseif ($order['payment_status'] === 'paid') {
                // Race-condition safety net: another request processed this
                // exact order between our DB pre-check above and this call.
                $paymentSuccessful = true;
                $orderItems = $OrderModel->getOrderItems($order['id']);
            } elseif ($paystackResult['data']['status'] !== 'success') {
                $failureMessage = 'Payment was not successful.';
            } else {
                $expectedAmountInKobo = (int) round($order['total'] * 100);
                $paidAmountInKobo = (int) ($paystackResult['data']['amount'] ?? 0);

                if ($paidAmountInKobo !== $expectedAmountInKobo) {
                    error_log('Paystack verify amount mismatch for order ' . $order['id'] . ' (reference ' . $reference . '): expected ' . $expectedAmountInKobo . ', got ' . $paidAmountInKobo);
                    $failureMessage = 'We could not automatically confirm your payment amount. Please contact support with your order reference.';
                } else {
                    $OrderModel->markAsPaid($order['id'], $reference);
                    $CartModel->clearCart(null, (int) $order['customer_id']);
                    $order = $OrderModel->getOrderById($order['id']);
                    $orderItems = $OrderModel->getOrderItems($order['id']);
                    $paymentSuccessful = true;
                }
            }
        }
    }
}

// Payment processing above is keyed by the order's own customer_id, not the
// current session — but showing order details is gated by ownership: don't
// let a mismatched or logged-out viewer see someone else's order.
if ($order && (!isset($_SESSION['customer_session']['id']) || (int) $_SESSION['customer_session']['id'] !== (int) $order['customer_id'])) {
    header('Location: ' . $basePath . '/');
    exit;
}

require "app/views/views.order_confirmation.php";
