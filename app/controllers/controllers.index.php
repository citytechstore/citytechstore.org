<?php
require_once('app/models/Database.php');
require_once('app/models/Product.php');

$homeProducts = $ProductModel->getAllProducts();
$heroProduct = $homeProducts[0] ?? null;
$flashDealProducts = $homeProducts;
$brandManufacturers = $ProductModel->getDistinctManufacturers();

require "app/views/views.index.php";
