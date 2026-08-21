<?php
session_start();
include '../db_connect.php';

if (isset($_SESSION['user_id'])) {
    // Log the logout action
    $user_id = $_SESSION['user_id'];
    $action = "Logged out";
    $log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action, timestamp) VALUES (?, ?, NOW())");
    $log_stmt->bind_param("is", $user_id, $action);
    $log_stmt->execute();
    $log_stmt->close();
}

// Destroy session
session_unset();
session_destroy();

// Redirect to homepage
header("Location: ../index.html");
exit();
?>
