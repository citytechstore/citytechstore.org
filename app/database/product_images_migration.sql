-- ============================================================
-- Product gallery support for citybxta_citytechstore
-- ============================================================
-- Additive only: does not drop or alter products.product_picture_url,
-- which stays in place as a fallback/thumbnail reference for existing
-- code (product_card.php, hero slides, etc).
-- ============================================================

USE citybxta_citytechstore;

CREATE TABLE IF NOT EXISTS product_images (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `display_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_product_images_product_id` (`product_id`)
);

-- One-time backfill: give every existing product its current
-- product_picture_url as gallery image #0.
INSERT INTO product_images (product_id, image_path, display_order)
SELECT id, product_picture_url, 0 FROM products
WHERE product_picture_url IS NOT NULL AND product_picture_url <> '';
