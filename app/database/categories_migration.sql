-- ============================================================
-- Categories master-data table for citybxta_citytechstore
-- ============================================================
-- Additive only: no DROP TABLE / DROP DATABASE statements here.
-- products.category is NOT altered or renamed by this migration — it
-- stays a plain VARCHAR, unchanged schema, unchanged existing rows.
-- This table is a controlled list of valid category *names* that the
-- staff "Add/Edit Product" forms now pick from via dropdown, instead
-- of free text. It is not (yet) a foreign key on products.category,
-- so this migration cannot break any existing product row.
--
-- Also unrelated to (and does not touch) the pre-existing
-- `product_category` table, which is a denormalized chart-data cache
-- used by the staff dashboard — see app/models/ProductCategory.php.
-- ============================================================

USE citybxta_citytechstore;

CREATE TABLE IF NOT EXISTS categories (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Migrate every distinct category value already present in
-- products.category (currently just "Phones") into the new table, so
-- the Add/Edit Product dropdowns have real starting options and
-- nothing about existing products changes. Safe to re-run.
INSERT INTO categories (name)
SELECT DISTINCT category AS name FROM products
WHERE category IS NOT NULL AND category <> ''
ON DUPLICATE KEY UPDATE name = VALUES(name);
