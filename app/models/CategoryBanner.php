<?php
require_once('Database.php');

class CategoryBanner {
    private $db;

    public function __construct($database_model) {
        $this->db = $database_model;
    }

    public function getBannerByCategoryId($categoryId) {
        $sql = "SELECT * FROM category_banners WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
        $banner = $result->fetch_assoc();
        return $banner ?: null;
    }

    // Shop page only knows the category by name (via ?c=category&p=<name>),
    // not its id, so this joins against categories rather than making the
    // caller resolve a name to an id first.
    public function getBannerByCategoryName($categoryName) {
        $sql = "SELECT category_banners.* FROM category_banners
                JOIN categories ON categories.id = category_banners.category_id
                WHERE categories.name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $categoryName);
        $stmt->execute();
        $result = $stmt->get_result();
        $banner = $result->fetch_assoc();
        return $banner ?: null;
    }

    public function getAllBanners() {
        $sql = "SELECT * FROM category_banners";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function saveBanner($categoryId, $imagePath, $headline, $subtext, $linkUrl) {
        $existing = $this->getBannerByCategoryId($categoryId);

        if ($existing) {
            $sql = "UPDATE category_banners
                    SET image_path = ?, headline = ?, subtext = ?, link_url = ?
                    WHERE category_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssi", $imagePath, $headline, $subtext, $linkUrl, $categoryId);
            return $stmt->execute();
        }

        $sql = "INSERT INTO category_banners (category_id, image_path, headline, subtext, link_url)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("issss", $categoryId, $imagePath, $headline, $subtext, $linkUrl);
        return $stmt->execute();
    }
}

$CategoryBannerModel = new CategoryBanner($DatabaseModel);
