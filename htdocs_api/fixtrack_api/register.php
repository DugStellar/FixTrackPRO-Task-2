<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
require_once "db_config.php";

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($full_name) || empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Check if user already exists
$checkStmt = $conn->prepare("SELECT user_id FROM users WHERE LOWER(email) = LOWER(?)");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Email address is already registered"]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();

// Securely hash the password using standard Bcrypt encryption
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$role = 'user';

$stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash, role) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Query preparation failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Registration successful! You can now log in."
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Registration failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>