<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

// Validate and get report ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Report not found.";
    exit();
}

$report_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// 🔒 Check ownership if officer
if ($role !== 'Administrator') {
    $check_stmt = $conn->prepare("SELECT report_id FROM reports WHERE report_id = ? AND officer_id = ?");
    $check_stmt->bind_param("ii", $report_id, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows === 0) {
        echo "You do not have permission to delete this report.";
        exit();
    }
    $check_stmt->close();
}

// 🗑️ Delete the report
$stmt = $conn->prepare("DELETE FROM reports WHERE report_id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$stmt->close();

// 📝 Log the deletion
$action = "Deleted report ID $report_id";
$log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
$log_stmt->bind_param("is", $user_id, $action);
$log_stmt->execute();
$log_stmt->close();

// Redirect back
header("Location: reports.php");
exit();
?>
