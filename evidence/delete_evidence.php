<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Invalid ID.");
}
$evidence_id = intval($_GET['id']);

// Delete from DB
$stmt = $conn->prepare("DELETE FROM evidence WHERE evidence_id = ?");
$stmt->bind_param("i", $evidence_id);
$stmt->execute();
$stmt->close();

header("Location: manage_evidence.php");
exit();