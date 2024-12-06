<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'final_de251');

// Create connection
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // Log error securely instead of displaying details
    error_log("Database Connection Failed: " . $conn->connect_error);
    die("Sorry, database connection error. Please try again later.");
}

// Set character set to ensure proper character handling
$conn->set_charset("utf8mb4");
?>