<?php
session_start();

require_once('app/models/Database.php');
require_once('app/models/Product.php');
require_once('app/models/CategoryBanner.php');

$categoryBanner = null;

if (isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['p']) && !empty($_GET['p'])) {
    $shopProducts = $ProductModel->getProductByCriteria(trim($_GET['p']), trim($_GET['c'])) ?? [];

    if (trim($_GET['c']) === 'category') {
        $categoryBanner = $CategoryBannerModel->getBannerByCategoryName(trim($_GET['p']));
    }
} else {
    $shopProducts = $ProductModel->getAllProducts();
}

require "app/views/views.shop.php";
