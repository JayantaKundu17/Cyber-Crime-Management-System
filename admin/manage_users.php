<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

// Handle delete
if (isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    header("Location: manage_users.php");
    exit();
}

// Fetch users
$result = $conn->query("SELECT user_id, name, email, role FROM users ORDER BY user_id ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #fff;
        }

        .container {
            max-width: 900px;
            margin: 60px auto;
            background: #ffffff10;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #ecf0f1;
        }

        .add-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #1abc9c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            color: #333;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background: #2980b9;
            color: white;
        }

        a.action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #3498db;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            padding: 8px 14px;
            background-color: #34495e;
            color: white;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Users</h2>

        <a href="add_user.php" class="add-button">+ Add New User</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <a class="action-btn edit-btn" href="edit_user.php?id=<?= $row['user_id'] ?>">Edit</a>
                        <a class="action-btn delete-btn" href="manage_users.php?delete=<?= $row['user_id'] ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <a class="back-btn" href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
</body>
</html>
