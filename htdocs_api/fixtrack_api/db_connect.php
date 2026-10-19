<?php
$host = "localhost";
$user = "root";
$password = ""; // XAMPP default password is empty
$database = "fixtrack_db"; // Make sure this matches your exact phpMyAdmin database name

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

// Set charset to utf8mb4 for standard text handling
$conn->set_charset("utf8mb4");
?>