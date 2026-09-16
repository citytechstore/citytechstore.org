<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "citytechstore";

// File path to SQL script
$sqlScript = "citytechstore.sql";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
    exit;
}

// Select database
$conn->select_db($database);

// Read SQL script
$sqlContent = file_get_contents($sqlScript);

// Execute SQL script
if ($conn->multi_query($sqlContent) === TRUE) {
    echo "Database schema created successfully\n";
} else {
    echo "Error creating database schema: " . $conn->error;
}

// Close connection
$conn->close();

?>
