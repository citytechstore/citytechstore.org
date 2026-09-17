<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_session']) || $_SESSION['loggedin'] != true || empty($_SESSION['user_session'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in as staff to do that.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once 'app/models/Database.php';
require_once 'app/models/ProductImage.php';

$imageId = filter_var($_POST['imageId'] ?? null, FILTER_VALIDATE_INT);
$productId = filter_var($_POST['productId'] ?? null, FILTER_VALIDATE_INT);

if ($imageId === false || $imageId <= 0 || $productId === false || $productId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid image.']);
    exit;
}

$removed = $ProductImageModel->deleteImage($imageId, $productId);

if ($removed) {
    echo json_encode(['success' => true, 'message' => 'Image removed.']);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Image not found.']);
}
