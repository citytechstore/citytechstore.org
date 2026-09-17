<?php
require_once('app/models/Database.php');
require_once('app/models/Product.php');

$brandManufacturers = $ProductModel->getDistinctManufacturers();

require "app/views/views.brands.php";
