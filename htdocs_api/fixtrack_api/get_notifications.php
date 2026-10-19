<?php
// Prevent PHP warnings/errors from corrupting JSON output
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");
require_once "db_config.php";

if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

$sql = "SELECT title, message FROM notifications ORDER BY broadcast_date DESC";
$result = $conn->query($sql);

$notifications = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = [
            "title" => $row['title'] ?? '',
            "message" => $row['message'] ?? ''
        ];
    }
}

echo json_encode([
    "status" => "success",
    "notifications" => $notifications,
    "raffle_ticket" => "FIX-99821"
]);

$conn->close();
?>