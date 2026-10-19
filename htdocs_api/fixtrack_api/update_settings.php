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

$reportId = intval($_POST['report_id'] ?? $json_data['report_id'] ?? 0);
$newStatus = trim($_POST['status'] ?? $json_data['status'] ?? '');

if ($reportId > 0 && !empty($newStatus)) {
    $stmt = $conn->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $reportId);
    
    if ($stmt->execute()) {
        if ($isApi) {
            echo json_encode(["status" => "success", "message" => "Status updated successfully"]);
        } else {
            header("Location: admin_dashboard.php?success=1");
        }
    } else {
        if ($isApi) {
            echo json_encode(["status" => "error", "message" => "Failed to update status"]);
        } else {
            echo "<script>alert('Failed to update status'); window.history.back();</script>";
        }
    }
    $stmt->close();
} else {
    if ($isApi) {
        echo json_encode(["status" => "error", "message" => "Invalid parameters"]);
    } else {
        echo "<script>alert('Invalid parameters'); window.history.back();</script>";
    }
}

$conn->close();
?>