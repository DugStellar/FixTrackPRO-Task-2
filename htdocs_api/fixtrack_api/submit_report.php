<?php
require_once 'db_config.php';

$user_id = $_POST['user_id'] ?? 1;
$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? 'General';
$description = $_POST['description'] ?? '';

if (empty($title) || empty($description)) {
    echo json_encode(["status" => "error", "message" => "Title and description required"]);
    exit();
}

$raffle_ticket = "FIX-" . rand(10000, 99999);

$stmt = $conn->prepare("INSERT INTO reports (user_id, title, category, description, status, raffle_ticket) VALUES (?, ?, ?, ?, 'Pending', ?)");
$stmt->bind_param("issss", $user_id, $title, $category, $description, $raffle_ticket);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Report submitted successfully!",
        "raffle_ticket" => $raffle_ticket
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to submit report"]);
}

$stmt->close();
$conn->close();
?>