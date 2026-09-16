-- Drop DB if exists
-- DROP DATABASE citybxta_citytechstore;

-- Create Database
CREATE DATABASE IF NOT EXISTS citybxta_citytechstore;

-- Use Database
USE citybxta_citytechstore;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    role ENUM('admin', 'worker', 'developer') NOT NULL,
        profile_picture VARCHAR(255) DEFAULT 'assets/img/defaults/user.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS
    products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        unit_price DECIMAL(10, 2) NOT NULL,
        quantity INT NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        manufacturer VARCHAR(255),
        category VARCHAR(100),
        uploaded_by INT, -- Changed to INT to reference the user who uploaded the product
        product_picture_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (uploaded_by) REFERENCES users (id) -- Foreign key to users table
    );

-- Create sales table
CREATE TABLE IF NOT EXISTS
    sales (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price_per_unit INT NOT NULL,
        total_price DECIMAL(10, 2) NOT NULL,
        amount_paid DECIMAL(10, 2),
        sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255),
        payment_method VARCHAR(100),
        customer_picture_url VARCHAR(255),
        worker_id INT, -- Changed to INT to reference the worker who made the sale
        FOREIGN KEY (product_id) REFERENCES products (id),
        FOREIGN KEY (worker_id) REFERENCES users (id) -- Foreign key to users table
    );



CREATE TABLE IF NOT EXISTS 
product_category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    quantity INT NOT NULL
);


CREATE TABLE IF NOT EXISTS stocks (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `activity_type` ENUM('add', 'sale') NOT NULL,
    `quantity` INT NOT NULL,
    `remaining_quantity` INT NOT NULL,
    `activity_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);


CREATE TABLE IF NOT EXISTS store_sections (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `section_name` VARCHAR(255),
    `section_image` TEXT DEFAULT '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]',
    `section_description` TEXT,
    `section_short_description` TEXT,
    `section_tags_description` TEXT
);



INSERT INTO store_sections (section_name, section_image, section_description, section_short_description, section_tags_description) VALUES
('Frame', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'High-quality frame for various devices.', 'Device Frame', 'frame, device, accessory'),
('Frame Cover', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Protective cover for device frames.', 'Frame Cover', 'cover, frame, protective'),
('Samsung Tab Touchpad', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Touchpad for Samsung tablets.', 'Samsung Tab Touchpad', 'touchpad, Samsung, tablet'),
('iPhone Tab Touchpad', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Touchpad for iPhone tablets.', 'iPhone Touchpad', 'touchpad, iPhone, tablet'),
('China Tab Touchpad', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Touchpad for Chinese tablets.', 'China Tab Touchpad', 'touchpad, China, tablet'),
('Fingerprint', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Fingerprint sensor for security.', 'Fingerprint Sensor', 'fingerprint, sensor, security'),
('Sub Board Flex', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Flexible sub-board for electronics.', 'Sub Board Flex', 'sub-board, flex, electronics'),
('Back Cover', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Back cover for various devices.', 'Device Back Cover', 'back cover, device, protection'),
('Power Flex', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Flexible power connector for electronics.', 'Power Flex', 'power, flex, connector'),
('Charging Panel', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Panel for charging devices.', 'Charging Panel', 'charging, panel, device'),
('Camera Glass', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Protective glass for camera lenses.', 'Camera Glass', 'camera, glass, lens'),
('Tools', '[
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg",
    "assets\/img\/sections\/8ce40ba28477bb8d8f8606758194c9fa.jpg"
]
', 'Various tools for device maintenance.', 'Device Tools', 'tools, maintenance, device');
