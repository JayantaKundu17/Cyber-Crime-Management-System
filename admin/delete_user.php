<?php
include '../db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Administrator') {
    header("Location: ../users/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "DELETE FROM Users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        header("Location: manage_users.php");
    } else {
        echo "Error deleting user.";
    }
}
?>