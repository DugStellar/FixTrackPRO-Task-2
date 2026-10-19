<?php
$host = "localhost";
$user = "root";
$password = ""; // Default XAMPP MySQL password is blank
$database = "fixtrack_db";

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>