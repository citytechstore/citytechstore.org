<?php
require_once('Database.php');

class ProductImage {
    private $db;

    public function __construct($database_model) {
        $this->db = $database_model;
    }

    public function getImagesByProductId($productId) {
        $sql = "SELECT * FROM product_images WHERE product_id = ? ORDER BY display_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addImage($productId, $imagePath, $displayOrder) {
        $sql = "INSERT INTO product_images (product_id, image_path, display_order) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("isi", $productId, $imagePath, $displayOrder);
        return $stmt->execute();
    }

    public function getNextDisplayOrder($productId) {
        $sql = "SELECT COALESCE(MAX(display_order), -1) + 1 AS next_order FROM product_images WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return (int) $result->fetch_assoc()['next_order'];
    }

    public function deleteImage($imageId, $productId) {
        // Scoped to product_id too, so one product's edit form can't be used
        // to delete another product's image by guessing an image id.
        $sql = "DELETE FROM product_images WHERE id = ? AND product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $imageId, $productId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}

$ProductImageModel = new ProductImage($DatabaseModel);
