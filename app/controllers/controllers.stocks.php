<?php
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_session']) || $_SESSION['loggedin'] != true || empty($_SESSION['user_session'])) {
    header("Location: /login");
}

// get all info
require ('app/models/Database.php'); // Database Model
require ('app/models/Stock.php'); // Stock Model
require ('app/models/Product.php'); // Product Model
require ('app/models/Sales.php'); // Sales Model
// require ('app/models/Users.php'); // Users Model
require ('app/models/ProductCategory.php'); // Product Category DB Model

// get all products, sales and users 
$_SESSION['products'] = $ProductModel->getAllProducts();
$_SESSION['sales'] = $SalesModel->getAllSales();
// $_SESSION['users'] = $UsersModel->getAllUsers();
// keep in session
$products = $_SESSION['products'] ?? [];
$sales = $_SESSION['sales'] ?? [];
$users = $_SESSION['users'] ?? [];
$total_products_price = array_sum(array_column($products, 'price'));
// $latest_activities = $_SESSION['latest_activities'] ?? [];

$stockActivities = $StockModel->getAllStockActivities();

require "app/views/views.stocks.php";

