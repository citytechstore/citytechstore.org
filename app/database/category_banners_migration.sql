-- ============================================================
-- Category banner support for citybxta_citytechstore
-- ============================================================
-- Additive only. Starts empty — staff create banners going forward,
-- there's no existing banner data to migrate.
--
-- One banner per category, enforced by the UNIQUE constraint on
-- category_id; CategoryBanner::saveBanner() upserts against it
-- (check-then-insert-or-update, matching Category::addCategory()'s
-- existing style in this codebase).
-- ============================================================

USE citybxta_citytechstore;

CREATE TABLE IF NOT EXISTS category_banners (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_id` INT NOT NULL UNIQUE,
    `image_path` VARCHAR(255) NOT NULL,
    `headline` VARCHAR(255) NOT NULL,
    `subtext` VARCHAR(255),
    `link_url` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
);
