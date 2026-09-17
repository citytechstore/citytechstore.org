<?php
require_once('Database.php');
// require_once('Database.php');

class Products {
    private $db;

    public function __construct($database_model) {
        $this->db = $database_model;
    }

    public function addProduct($product_arr) {
        try {
            if($this->db->array_insert('products', $product_arr)){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Failed to add product: " . $e->getMessage());
        }
    }

    public function getProduct($product_id) {
        // Query the database to get product details
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function getAllProducts() {
        // Query the database to get all products
        $sql = "SELECT * FROM products";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getDistinctCategories() {
        $sql = "SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getDistinctManufacturers() {
        $sql = "SELECT DISTINCT manufacturer FROM products WHERE manufacturer IS NOT NULL AND manufacturer <> '' ORDER BY manufacturer ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductsByCategory($category, $limit = 8) {
        $sql = "SELECT * FROM products WHERE category = ? LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $category, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductByCriteria($value, $criteria = 'name') {
        // Default criteria is 'name' if not specified
        $criteria = in_array($criteria, ['name', 'manufacturer', 'category', 'product_picture_url']) ? $criteria : 'name';

        // Query the database to get product details by name, manufacturer, or category using LIKE keyword
        $sql = "SELECT * FROM products WHERE $criteria LIKE ?";
        $stmt = $this->db->prepare($sql);
        $searchValue = "%$value%"; // Add wildcards to search for partial matches
        $stmt->bind_param("s", $searchValue);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return null;
        }
    }

    public function getProductById($id) {
        $query = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProductQuantity($id, $newQuantity) {
        $query = "UPDATE products SET quantity = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $newQuantity, $id);

        return $stmt->execute();
    }
    public function arrayUpdateProduct($data, $id)
    {
        $sql = "UPDATE products SET ";
        foreach ($data as $col => $value) {
            $sql .= "$col='$value', ";
        }
        $sql = implode(',', explode(',', $sql, -1)) . " WHERE id = '$id'";
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }

    // public function take_stock($productId, $activityType, $quantity, $remainingQuantity) {
    //     require_once('Stock.php');
    //     $stockModel = new Stock($this->conn);
    //     $data = [
    //         'product_id' => $productId,
    //         'activity_type' => $activityType,
    //         'quantity' => $quantity,
    //         'remaining_quantity' => $remainingQuantity
    //     ];
    //     return $stockModel->recordStockActivity($data);
    // }

}


$ProductModel = new Products($DatabaseModel);
