<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Log activity
$action = "Viewed cases";
$log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
$log_stmt->bind_param("is", $user_id, $action);
$log_stmt->execute();
$log_stmt->close();

// Fetch cases
if ($role === 'Administrator') {
    $query = "SELECT cases.*, users.name AS officer_name FROM cases LEFT JOIN users ON cases.officer_id = users.user_id ORDER BY date_reported DESC";
} else {
    $query = "SELECT cases.*, users.name AS officer_name FROM cases LEFT JOIN users ON cases.officer_id = users.user_id WHERE officer_id = $user_id ORDER BY date_reported DESC";
}
$result = $conn->query($query);

// Determine back button
$backUrl = ($role === 'Administrator') ? '../admin/admin_dashboard.php' : '../officers/officer_dashboard.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cases</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 1100px;
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

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-btn, .back-btn {
            padding: 10px 16px;
            background-color: #1abc9c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-btn {
            background-color: #555;
        }

        .add-btn:hover {
            background-color: #16a085;
        }

        .back-btn:hover {
            background-color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        .action-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            white-space: nowrap;
        }

        .edit-btn { background-color: #f39c12; }
        .edit-btn:hover { background-color: #e67e22; }

        .delete-btn { background-color: #e74c3c; }
        .delete-btn:hover { background-color: #c0392b; }

        .no-action {
            color: #aaa;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h2>Manage Cases</h2>
            <div>
                <a class="add-btn" href="add_case.php">+ Add Case</a>
                <a class="back-btn" href="<?= $backUrl ?>">← Back to Dashboard</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Case ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date Reported</th>
                    <th>Officer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['case_id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= $row['date_reported'] ?></td>
                            <td><?= htmlspecialchars($row['officer_name'] ?? 'N/A') ?></td>
                            <td>
                                <?php if ($role === 'Administrator' || $row['officer_id'] == $user_id): ?>
                                    <div class="action-container">
                                        <a href="edit_case.php?case_id=<?= $row['case_id'] ?>" class="action-btn edit-btn">Edit</a>
                                        <a href="delete_case.php?id=<?= $row['case_id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this case?')">Delete</a>
                                    </div>
                                <?php else: ?>
                                    <span class="no-action">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No cases found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
