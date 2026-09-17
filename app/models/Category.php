<?php
require_once('Database.php');

class Category {
    private $db;

    public function __construct($database_model) {
        $this->db = $database_model;
    }

    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCategory($name) {
        $sql = "SELECT id FROM categories WHERE name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            throw new Exception("A category named \"$name\" already exists.");
        }

        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $name);
        return $stmt->execute();
    }
}

$CategoryModel = new Category($DatabaseModel);
