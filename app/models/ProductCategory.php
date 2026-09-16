<?php
// require_once ('../database/db_config.php'); // dev
require_once ('app/database/db_config.php'); // prod

class ProductCategory
{
    private $db;

    public function __construct($database_connection)
    {
        $this->db = $database_connection;
    }

    // Method to populate the product_category table
    public function populateProductCategoryTable()
    {
        // Truncate the table to clear existing data
        $this->db->query("TRUNCATE TABLE product_category");

        // Query to gather all unique categories and sum the quantities
        $query = "SELECT category, SUM(quantity) AS total_quantity FROM products GROUP BY category";

        // Execute the query
        $result = $this->db->query($query);

        // Check if query was successful
        if ($result) {
            // Fetch rows and insert into product_category table
            while ($row = $result->fetch_assoc()) {
                $category = $row['category'];
                $quantity = $row['total_quantity'];

                // Insert into product_category table
                $insert_query = "INSERT INTO product_category (category, quantity) VALUES ('$category', $quantity)";
                $this->db->query($insert_query);
            }
            return true; // Successful population
        } else {
            return false; // Failed population
        }
    }

    // Method to get data for chart generation
    public function getProductCategoryChartData()
    {
        $chart_data = [];
        $query = "SELECT category, quantity FROM product_category";
        $result = $this->db->query($query);

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $chart_data[] = [
                    'category' => $row['category'],
                    'quantity' => $row['quantity']
                ];
            }
        }

        return $chart_data;
    }
}



$ProductCategoryDB = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$productCategoryModel = new ProductCategory($ProductCategoryDB);

// Populate the product_category table
if ($productCategoryModel->populateProductCategoryTable()) {

    // Get data for chart generation
    $chartData = $productCategoryModel->getProductCategoryChartData();
    $labels = [];
    $labelsCount = [];

    foreach ($chartData as $item => $key) {
        $labels[] = $key['category'];
        $labelsCount[] = (int) $key['quantity'];
    }

    $jsLabel = json_encode($labels);
    $jsLabelCount = json_encode($labelsCount);
} else {
    echo "Failed to populate product category table.";
}

// echo $jsLabel;