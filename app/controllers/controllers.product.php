<?php
require_once('app/models/Database.php');
require_once('app/models/Product.php');
require_once('app/models/ProductImage.php');

$productId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$product = $productId ? $ProductModel->getProductById($productId) : null;

if (!$product) {
    http_response_code(404);
    require "app/views/views.product_not_found.php";
    exit;
}

$productImages = $ProductImageModel->getImagesByProductId($productId);
if (empty($productImages)) {
    // Fall back to the single legacy image so products that predate the
    // gallery (or were never migrated) still show something.
    $productImages = [['image_path' => $product['product_picture_url']]];
}

$relatedProducts = array_filter(
    $ProductModel->getProductsByCategory($product['category']),
    fn($related) => (int) $related['id'] !== (int) $product['id']
);

require "app/views/views.product.php";
