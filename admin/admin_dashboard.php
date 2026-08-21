<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../users/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #fff;
        }

        .dashboard-container {
            max-width: 600px;
            margin: 80px auto;
            background: #ffffff10;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 30px;
            color: #ecf0f1;
        }

        .dashboard-button {
            display: block;
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .dashboard-button:hover {
            background-color: #1abc9c;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <img src="../assets/bank-fraud.png" alt="Logo" class="logo">
        <h2>Welcome, Admin!</h2>
        <a href="manage_users.php" class="dashboard-button">Manage Users</a>
        <a href="../cases/manage_cases.php" class="dashboard-button">Case Management</a>
        <a href="../audit_log/view_logs.php" class="dashboard-button">View Audit Logs</a>
        <a href="../reports/reports.php" class="dashboard-button">View Reports</a>
        <a href="../evidence/manage_evidence.php" class="dashboard-button">Manage Evidence</a>
        <a href="../users/logout.php" class="dashboard-button">Logout</a>
    </div>
</body>
</html>
