<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if (isset($_GET['case_id'])) {
    $case_id = intval($_GET['case_id']);

    $stmt = $conn->prepare("DELETE FROM cases WHERE case_id = ?");
    $stmt->bind_param("i", $case_id);
    $stmt->execute();
}

header("Location: manage_cases.php");
exit();