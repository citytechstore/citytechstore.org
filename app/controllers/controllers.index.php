<?php
require_once('app/models/Database.php');
require_once('app/models/Product.php');

$homeProducts = $ProductModel->getAllProducts();
$brandManufacturers = $ProductModel->getDistinctManufacturers();

// Hero carousel slides — built from real product data (name, price,
// category), not fake marketing content. No product image is used yet: the
// uploaded product photos turned out to be scraped Samsung/Apple marketing
// art (and in one case an unrelated screenshot), not real inventory photos,
// so each slide renders as a branded placeholder panel until real photos
// are uploaded. Swapping to real photos later only means adding an 'image'
// key here — the view's rendering loop doesn't change.
$heroSlides = [];
foreach (array_slice($homeProducts, 0, 3) as $product) {
    $heroSlides[] = [
        'badge' => 'Now In Stock',
        'title' => $product['name'],
        'description' => 'Now available for ' . "\u{20A6}" . number_format($product['unit_price'], 2) . ' at ' . APP_NAME . '.',
        'link' => 'shop?c=category&p=' . urlencode($product['category']),
        'linkText' => 'Shop Now',
        'icon' => 'fa-mobile-alt',
    ];
}

$homeCategoryDefs = [
    ['name' => 'Phones', 'icon' => 'fa-mobile-alt'],
    ['name' => 'Laptops', 'icon' => 'fa-laptop'],
    ['name' => 'Accessories', 'icon' => 'fa-plug'],
    ['name' => 'Audio', 'icon' => 'fa-headphones'],
    ['name' => 'Gaming', 'icon' => 'fa-gamepad'],
];
$homeCategoryStrips = [];
foreach ($homeCategoryDefs as $def) {
    $homeCategoryStrips[] = [
        'title' => $def['name'],
        'category' => $def['name'],
        'icon' => $def['icon'],
        'products' => $ProductModel->getProductsByCategory($def['name']),
    ];
}

require "app/views/views.index.php";
