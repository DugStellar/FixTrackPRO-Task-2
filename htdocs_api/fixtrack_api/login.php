<?php
// Ensure NO HTML or extra warnings output
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');

require_once 'db_connect.php';

// Accept both standard POST form data AND raw JSON body payloads
$raw_input = file_get_contents('php://input');
$json_data = json_decode($raw_input, true);

$email = '';
$password = '';

if (!empty($_POST['email'])) {
    $email = trim($_POST['email']);
} elseif (isset($json_data['email'])) {
    $email = trim($json_data['email']);
}

if (!empty($_POST['password'])) {
    $password = trim($_POST['password']);
} elseif (isset($json_data['password'])) {
    $password = trim($json_data['password']);
}

if (empty($email) || empty($password)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide both email and password"
    ]);
    exit();
}

if (!$conn) {
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed"
    ]);
    exit();
}

// Query user by email
$stmt = $conn->prepare("SELECT user_id, full_name, email, password_hash, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $db_password = $user['password_hash'];
    $role = trim($user['role']);

    if ($password === $db_password || password_verify($password, $db_password)) {
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "id" => (int)$user['user_id'],
                "name" => $user['full_name'],
                "email" => $user['email'],
                "role" => $role
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "User not found"
    ]);
}

$stmt->close();
$conn->close();
?>