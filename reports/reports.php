<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Log the viewing activity
$log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
$action = "Viewed reports list";
$log_stmt->bind_param("is", $user_id, $action);
$log_stmt->execute();
$log_stmt->close();

// Fetch reports
if ($role === 'Administrator') {
    $query = "
        SELECT r.*, u.name AS officer_name
        FROM reports r
        LEFT JOIN users u ON r.officer_id = u.user_id
        ORDER BY r.report_date DESC
    ";
} else {
    $query = "
        SELECT r.*, u.name AS officer_name
        FROM reports r
        LEFT JOIN users u ON r.officer_id = u.user_id
        WHERE r.officer_id = $user_id
        ORDER BY r.report_date DESC
    ";
}
$reports = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <style>
        body {
            background: linear-gradient(to right, #2c3e50, #3498db);
            font-family: 'Segoe UI', sans-serif;
            color: white;
            padding: 40px;
        }

        .container {
            background: white;
            color: black;
            padding: 30px;
            border-radius: 12px;
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        a.btn {
            background-color: #1abc9c;
            padding: 10px 16px;
            text-decoration: none;
            color: white;
            border-radius: 6px;
        }

        a.btn:hover {
            background-color: #16a085;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        .action-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .action-btns a {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            white-space: nowrap;
        }

        .edit-btn {
            background-color: #f39c12;
        }

        .edit-btn:hover {
            background-color: #e67e22;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .delete-btn, .edit-btn {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reports</h2>

        <div class="top-bar">
            <a class="btn" href="add_report.php">+ Add Report</a>
            <a class="btn" href="<?= $role === 'Administrator' ? '../admin/admin_dashboard.php' : '../officers/officer_dashboard.php' ?>">← Back to Dashboard</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Case ID</th>
                    <th>Details</th>
                    <th>Officer</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $reports->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['report_id'] ?></td>
                        <td><?= $row['case_id'] ?></td>
                        <td><?= htmlspecialchars($row['report_details']) ?></td>
                        <td><?= htmlspecialchars($row['officer_name'] ?? 'N/A') ?></td>
                        <td><?= $row['report_date'] ?></td>
                        <td class="action-btns">
                            <?php if ($role === 'Administrator' || $row['officer_id'] == $user_id): ?>
                                <a class="btn edit-btn" href="edit_report.php?id=<?= $row['report_id'] ?>">Edit</a>
                                <a class="btn delete-btn" href="delete_report.php?id=<?= $row['report_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                            </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
