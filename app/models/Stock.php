<?php
require_once('Database.php');
class Stock {
    private $database_model;
    private $table_name = "stocks";

    public function __construct($db) {
        $this->database_model = $db;
    }

    public function recordStockActivity($data) {
        $query = "INSERT INTO " . $this->table_name . "
                  (product_id, activity_type, quantity, remaining_quantity)
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->database_model->prepare($query);

        // Bind data
        $stmt->bind_param("isii",
            $data['product_id'],
            $data['activity_type'],
            $data['quantity'],
            $data['remaining_quantity']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllStockActivities() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY activity_date DESC";
        $stmt = $this->database_model->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

$StockModel = new Stock($DatabaseModel);