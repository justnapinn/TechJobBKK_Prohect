<?php
// Database Configuration
$host = 'db';
$user = 'MYSQL_USER';
$pass = 'MYSQL_PASSWORD';
$db = 'MYSQL_DATABASE';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    // Log error securely instead of displaying details
    error_log("Database Connection Failed: " . $conn->connect_error);
    die("Sorry, database connection error. Please try again later.");
}

// Set character set to ensure proper character handling
$conn->set_charset("utf8mb4");
?>