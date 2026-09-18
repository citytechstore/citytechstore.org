-- ============================================================
-- E-commerce schema addition for citybxta_citytechstore
-- ============================================================
-- Additive only: no DROP DATABASE / DROP TABLE statements here.
-- Assumes the database already exists (created by citytechstore.sql).
-- Does NOT modify or reuse the existing staff-only `users` table,
-- or the staff-recorded `sales` table — those stay untouched.
-- ============================================================

USE citybxta_citytechstore;

-- ------------------------------------------------------------
-- customers
-- Customer-facing accounts (registration/login on the storefront).
-- Deliberately separate from `users`, which is staff-only
-- (admin/worker) and has no concept of a storefront login.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS customers (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL, -- hashed (password_hash()), never plain text.
                                      -- For Google-only accounts this is a hash of a
                                      -- random, never-disclosed value (see
                                      -- Customer::registerWithGoogle()) rather than a
                                      -- nullable column, so this constraint still holds.
    `phone_number` VARCHAR(20),
    `google_id` VARCHAR(255) NULL UNIQUE, -- Google's stable "sub" claim; NULL for
                                           -- password-based accounts
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- addresses
-- Saved delivery addresses for a customer (e.g. "Home", "Office").
-- A customer can have several; `is_primary` marks the default one
-- used to prefill checkout.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS addresses (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `label` VARCHAR(100) DEFAULT 'Home',
    `full_address` TEXT NOT NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `phone_number` VARCHAR(20),
    `is_primary` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
    INDEX `idx_addresses_customer_id` (`customer_id`)
);

-- ------------------------------------------------------------
-- cart_items
-- Items sitting in a shopping cart before checkout. Supports both
-- logged-in customers (customer_id) and guest carts (session_id) —
-- exactly one of the two is expected to be set per row, enforced in
-- application code rather than SQL, since MySQL has no clean way to
-- express "exactly one of these two columns is non-null" as a constraint.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS cart_items (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NULL,
    `session_id` VARCHAR(255) NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_cart_items_customer_id` (`customer_id`),
    INDEX `idx_cart_items_session_id` (`session_id`),
    INDEX `idx_cart_items_product_id` (`product_id`)
);

-- ------------------------------------------------------------
-- orders
-- Customer-facing orders placed through the storefront/checkout.
-- Separate from the existing `sales` table, which records
-- staff-entered, in-person sales — the two are not the same thing.
-- payment_reference stores the Paystack transaction reference for
-- verifying/reconciling payment server-side.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `order_number` VARCHAR(50) NOT NULL UNIQUE, -- e.g. CTS-2025-00847
    `address_id` INT NOT NULL,
    `subtotal` DECIMAL(10, 2) NOT NULL,
    `delivery_fee` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    `total` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    `payment_status` ENUM('pending', 'paid', 'failed') NOT NULL DEFAULT 'pending',
    `payment_reference` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`),
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`),
    INDEX `idx_orders_customer_id` (`customer_id`),
    INDEX `idx_orders_payment_reference` (`payment_reference`)
);

-- ------------------------------------------------------------
-- order_items
-- Line items belonging to an order. `price_at_purchase` freezes the
-- unit price at checkout time — it must NOT be a live lookup against
-- products.unit_price, since that price can change later and would
-- silently rewrite the historical value of a past order.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `price_at_purchase` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
    INDEX `idx_order_items_order_id` (`order_id`),
    INDEX `idx_order_items_product_id` (`product_id`)
);

-- ------------------------------------------------------------
-- wishlist
-- Products a customer has saved for later. A customer can only save
-- the same product once (unique_customer_product).
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS wishlist (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_customer_product` (`customer_id`, `product_id`),
    INDEX `idx_wishlist_customer_id` (`customer_id`),
    INDEX `idx_wishlist_product_id` (`product_id`)
);
