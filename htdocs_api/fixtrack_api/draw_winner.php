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

$raffleId = intval($_POST['raffle_id'] ?? $json_data['raffle_id'] ?? 1);

// Select a random entry from raffle_entries
$query = "SELECT e.ticket_number, u.full_name, u.email 
          FROM raffle_entries e 
          JOIN users u ON e.user_id = u.user_id 
          WHERE e.raffle_id = ? 
          ORDER BY RAND() LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $raffleId);
$stmt->execute();
$result = $stmt->get_result();

if ($winner = $result->fetch_assoc()) {
    if ($isApi) {
        echo json_encode([
            "status" => "success",
            "message" => "Winner selected!",
            "winner" => [
                "name" => $winner['full_name'],
                "email" => $winner['email'],
                "ticket" => $winner['ticket_number']
            ]
        ]);
    } else {
        echo "<script>alert('🎉 WINNER DRAWN: " . $winner['full_name'] . " (" . $winner['ticket_number'] . ")'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    if ($isApi) {
        echo json_encode(["status" => "error", "message" => "No entries found for this raffle"]);
    } else {
        echo "<script>alert('No entries found for this raffle'); window.history.back();</script>";
    }
}

$stmt->close();
$conn->close();
?>