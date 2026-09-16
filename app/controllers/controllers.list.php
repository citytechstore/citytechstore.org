<?php 
session_start();

// // get all info
require('app/models/Database.php'); // Database Model
require('app/models/Product.php'); // Product Model
require('app/models/Sales.php'); // Sales Model
require('app/models/Users.php'); // Users Model
require('app/models/ProductCategory.php'); // Product Category DB Model
require('app/models/lib.php');

$_SESSION['products'] = $ProductModel->getAllProducts();
$_SESSION['sales'] = $SalesModel->getAllSales();
$_SESSION['users'] = $UsersModel->getAllUsers();


// // keep in session
$products = $_SESSION['products'] ?? [];

// print_r($products);
$sales = $_SESSION['sales'] ?? [];
$users = $_SESSION['users'] ?? [];
$latest_activities = $_SESSION['latest_activities'] ?? [];

// get all manufacturer list
// print_r($all_manufacturers);

function echoSearchForm(){
    echo '
    <div class="mb-3">
                    <form role="search"class="d-flex" method="GET">
                        <select name="searchCriteria" id="criteria" class="form-control me-2" required>
                            <option value="categories">Categories</option>
                            <option value="name">Name</option>
                            <option value="manufacturer">Manufacturer</option>
                            <option value="category">Category</option>
                        </select>
                        <input class="form-control" type="search" placeholder="Search for product"
                            aria-label="Search" name="searchInput" id="searchInput" value="" required>

                            <button class="ms-2 btn btn-primary" name="searchProduct" id="searchProduct" type="submit">Go</button>
                    </form>
                </div>
    ';
}
require "app/views/views.list.php";