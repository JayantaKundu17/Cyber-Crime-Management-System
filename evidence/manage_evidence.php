<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Determine back URL based on role
$backUrl = ($role === 'Administrator') ? '../admin/admin_dashboard.php' : '../officers/officer_dashboard.php';

// Log activity
$action = "Viewed evidence";
$log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
$log_stmt->bind_param("is", $user_id, $action);
$log_stmt->execute();
$log_stmt->close();

// Fetch evidence data
if ($role === 'Administrator') {
    $query = "SELECT evidence.*, cases.title AS case_title FROM evidence LEFT JOIN cases ON evidence.case_id = cases.case_id ORDER BY evidence.upload_date DESC";
} else {
    $query = "SELECT evidence.*, cases.title AS case_title FROM evidence 
              LEFT JOIN cases ON evidence.case_id = cases.case_id 
              WHERE cases.officer_id = $user_id ORDER BY evidence.upload_date DESC";
}
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Evidence</title>
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
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .add-btn, .back-btn {
            padding: 10px 16px;
            background-color: #1abc9c;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 5px 0;
        }

        .add-btn:hover, .back-btn:hover {
            background-color: #16a085;
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

        .action-btn {
            padding: 6px 10px;
            margin-right: 10px; /* Adds space between buttons */
            border-radius: 5px;
            color: white;
            text-decoration: none;
            display: inline-block; /* Makes sure buttons are inline */
            width: auto; /* Allows the button to adjust width based on text */
            white-space: nowrap; /* Prevents buttons from breaking into multiple lines */
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
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <h2>Manage Evidence</h2>
            <div>
                <a class="add-btn" href="add_evidence.php">+ Add Evidence</a>
                <a class="back-btn" href="<?= $backUrl ?>">← Back to Dashboard</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Evidence ID</th>
                    <th>Case Title</th>
                    <th>Description</th>
                    <th>File</th>
                    <th>Upload Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['evidence_id'] ?></td>
                            <td><?= htmlspecialchars($row['case_title']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td>
                                <?php if ($row['file_path']): ?>
                                    <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">View File</a>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td><?= $row['upload_date'] ?></td>
                            <td>
                                <a href="edit_evidence.php?id=<?= $row['evidence_id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="delete_evidence.php?id=<?= $row['evidence_id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this evidence?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No evidence found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
