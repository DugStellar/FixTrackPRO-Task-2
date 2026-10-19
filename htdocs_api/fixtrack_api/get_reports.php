<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'db_config.php';

$role = $_GET['role'] ?? 'guest';

// Query to get reports
$sql = "SELECT id, title, category, description, status, upvotes, raffle_ticket, created_at FROM reports ORDER BY created_at DESC";
$result = $conn->query($sql);

$reports = array();

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = array(
            "id" => (int)$row['id'],
            "title" => $row['title'] ?? 'No Title',
            "category" => $row['category'] ?? 'General',
            "description" => $row['description'] ?? '',
            "status" => $row['status'] ?? 'Pending',
            "upvotes" => (int)($row['upvotes'] ?? 0),
            "raffle_ticket" => $row['raffle_ticket'] ?? '',
            "created_at" => $row['created_at'] ?? ''
        );
    }
} else {
    // Fallback sample data if database table is empty
    $reports[] = array(
        "id" => 1,
        "title" => "Wi-Fi Down in Block B",
        "category" => "Network",
        "description" => "Cannot connect to student portal network.",
        "status" => "Pending",
        "upvotes" => 5,
        "raffle_ticket" => "FIX-10421",
        "created_at" => "2026-10-19 12:00:00"
    );
}

echo json_encode(array(
    "status" => "success",
    "role_view" => $role,
    "reports" => $reports
));

$conn->close();
?>