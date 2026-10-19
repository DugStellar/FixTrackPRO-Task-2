<?php
// Set response header to JSON format
header("Content-Type: application/json; charset=UTF-8");

// Database credentials
$host     = "localhost";
$db_name  = "fixtrack_db";
$username = "root";
$password = ""; // Default XAMPP password is empty

try {
    // Create a new PDO database connection
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Output JSON success message when accessed directly in browser (for testing/screenshots)
    if (basename($_SERVER['PHP_SELF']) == 'db.php') {
        echo json_encode([
            "status"  => "success",
            "message" => "Database connection established successfully.",
            "database" => $db_name
        ], JSON_PRETTY_PRINT);
    }

} catch (PDOException $exception) {
    // Output error response if connection fails
    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Database connection failed: " . $exception->getMessage()
    ], JSON_PRETTY_PRINT);
    exit();
}
?>