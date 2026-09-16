<?php
require_once('Database.php');

class Sales {
    private $db;

    public function __construct($database_model) {
        $this->db = $database_model;
    }

    public function addSales($sales_arr) {
        try {
            if($this->db->array_insert('sales', $sales_arr)){
                return true;
            }else{
                return false;
            }
        } catch (Exception $e) {
            throw new Exception("Failed to add sale: " . $e->getMessage());
        }
    }

    public function getSale($sales_id) {
        // Query the database to get sale details
        $sql = "SELECT * FROM sales WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $sales_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function getAllSales() {
        // Query the database to get all sales
        $sql = "SELECT * FROM sales";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getSaleByCriteria($value, $criteria = 'customer_name') {
        // Default criteria is 'customer_name' if not specified
        $criteria = in_array($criteria, ['customer_name', 'payment_method', 'worker_id']) ? $criteria : 'customer_name';

        // Query the database to get sale details by customer name, payment method, or worker name using LIKE keyword
        $sql = "SELECT * FROM sales WHERE $criteria LIKE ?";
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

$SalesModel = new Sales($DatabaseModel);