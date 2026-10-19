<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

require_once 'db_connect.php';

$isApi = (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) 
      || (!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
      || (isset($_SERVER['HTTP_USER_AGENT']) && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'dalvik') !== false);

if ($isApi) {
    header('Content-Type: application/json; charset=utf-8');
}

$raw_input = file_get_contents('php://input');
$json_data = json_decode($raw_input, true);

$action = trim($_POST['action'] ?? $json_data['action'] ?? 'get');

// If action is fetch/get list
if ($action === 'get' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT * FROM prizes ORDER BY id DESC");
    $prizes = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $prizes[] = [
                "id" => (int)$row['id'],
                "prize_name" => $row['prize_name'] ?? '',
                "description" => $row['description'] ?? '',
                "quantity" => (int)($row['quantity'] ?? 1)
            ];
        }
    }
    echo json_encode([
        "status" => "success",
        "prizes" => $prizes
    ]);
    exit();
}

// Handle Add New Prize
$prizeName = trim($_POST['prize_name'] ?? $json_data['prize_name'] ?? '');
$description = trim($_POST['description'] ?? $json_data['description'] ?? '');
$quantity = intval($_POST['quantity'] ?? $json_data['quantity'] ?? 1);

if (empty($prizeName)) {
    if ($isApi) echo json_encode(["status" => "error", "message" => "Prize name is required"]);
    else echo "<script>alert('Prize name is required'); window.history.back();</script>";
    exit();
}

$stmt = $conn->prepare("INSERT INTO prizes (prize_name, description, quantity) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $prizeName, $description, $quantity);

if ($stmt->execute()) {
    if ($isApi) {
        echo json_encode(["status" => "success", "message" => "Prize added successfully!"]);
    } else {
        echo "<script>alert('Prize added successfully!'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    if ($isApi) {
        echo json_encode(["status" => "error", "message" => "Failed to save prize"]);
    } else {
        echo "<script>alert('Failed to save prize'); window.history.back();</script>";
    }
}

$stmt->close();
$conn->close();
?>