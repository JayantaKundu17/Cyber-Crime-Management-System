<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Handle log clearing
if ($role === 'Administrator' && isset($_POST['clear_logs'])) {
    $conn->query("DELETE FROM audit_log");
    header("Location: view_logs.php");
    exit();
}

// 🟢 Log the view action
$action = "Viewed activity logs";
$log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
$log_stmt->bind_param("is", $user_id, $action);
$log_stmt->execute();
$log_stmt->close();

// 🟢 Fetch logs
if ($role === 'Administrator') {
    $query = "SELECT audit_log.*, users.name FROM audit_log LEFT JOIN users ON audit_log.user_id = users.user_id ORDER BY audit_log.timestamp DESC";
} else {
    $query = "SELECT audit_log.*, users.name FROM audit_log LEFT JOIN users ON audit_log.user_id = users.user_id WHERE audit_log.user_id = $user_id ORDER BY audit_log.timestamp DESC";
}
$result = $conn->query($query);

// 🟢 Back button URL
$backUrl = ($role === 'Administrator') ? '../admin/admin_dashboard.php' : '../officers/officer_dashboard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Logs</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-container a,
        .btn-container form {
            display: inline-block;
        }

        .back-btn,
        .clear-btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .back-btn {
            background-color: #555;
            color: white;
        }

        .back-btn:hover {
            background-color: #333;
        }

        .clear-btn {
            background-color: #e74c3c;
            color: white;
        }

        .clear-btn:hover {
            background-color: #c0392b;
        }
    </style>
    <script>
        function confirmClear() {
            return confirm("Are you sure you want to clear all activity logs?");
        }
    </script>
</head>
<body>
    <div class="container">
        <h2><?= $role === 'Administrator' ? 'All User Logs' : 'Your Activity Logs' ?></h2>

        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($row['action']) ?></td>
                        <td><?= $row['timestamp'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="btn-container">
            <a class="back-btn" href="<?= $backUrl ?>">← Back to Dashboard</a>

            <?php if ($role === 'Administrator'): ?>
                <form method="post" onsubmit="return confirmClear();">
                    <input type="hidden" name="clear_logs" value="1">
                    <button type="submit" class="clear-btn">🗑 Clear All Logs</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
