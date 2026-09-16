<?php
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_session']) || $_SESSION['loggedin'] != true || empty($_SESSION['user_session'])) {
    header("Location: /login");
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: /login");
    exit();
}

// get all info
require('app/models/Database.php'); // Database Model
require('app/models/Product.php'); // Product Model
require('app/models/Sales.php'); // Sales Model
require('app/models/Users.php'); // Users Model
require('app/models/ProductCategory.php'); // Product Category DB Model


// get all products, sales and users 
$_SESSION['products'] = $ProductModel->getAllProducts();
$_SESSION['sales'] = $SalesModel->getAllSales();
$_SESSION['users'] = $UsersModel->getAllUsers();

// keep in session
$products = $_SESSION['products'] ?? [];
$sales = $_SESSION['sales'] ?? [];
$users = $_SESSION['users'] ?? [];
$latest_activities = $_SESSION['latest_activities'] ?? [];

// total sales and products price
$total_sales_price = array_sum(array_column($sales, 'total_price'));
$total_products_price = array_sum(array_column($products, 'total_price'));

// total products and sales count, plus users
$total_products_count = count($products);
$total_sales_count = count($sales);
$total_users = count($users); 

$sales_data = json_encode([
    0,0,0,
    0,$total_sales_count,0,
    0,0,0,
    0,0,0]);

// print_r($users);
require "app/views/views.dashboard.php";
