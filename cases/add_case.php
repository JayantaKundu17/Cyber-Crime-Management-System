<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

// Fetch officers for the dropdown
$officers = $conn->query("SELECT user_id, name FROM users WHERE role = 'Officer'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $date_reported = $_POST['date_reported'];
    $officer_id = $_POST['officer_id'];

    // Insert case into database
    $stmt = $conn->prepare("INSERT INTO cases (title, description, status, date_reported, officer_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $status, $date_reported, $officer_id);
    $stmt->execute();

    // Log the action
    $user_id = $_SESSION['user_id'];
    $action = "Added new case: $title (Officer ID: $officer_id)";
    $log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
    $log_stmt->bind_param("is", $user_id, $action);
    $log_stmt->execute();

    header("Location: manage_cases.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Case</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #fff;
        }

        .form-container {
            max-width: 600px;
            margin: 60px auto;
            background: #ffffff15;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #ecf0f1;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1abc9c;
            color: white;
            border: none;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #16a085;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #ecf0f1;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Case</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Case Title" required>
            <textarea name="description" placeholder="Case Description" rows="4" required></textarea>

            <select name="status" required>
                <option value="">Select Status</option>
                <option value="Open">Open</option>
                <option value="Under Investigation">Under Investigation</option>
                <option value="Closed">Closed</option>
            </select>

            <input type="date" name="date_reported" required>

            <select name="officer_id" required>
                <option value="">Assign Officer</option>
                <?php while ($row = $officers->fetch_assoc()): ?>
                    <option value="<?= $row['user_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Submit Case</button>
        </form>
        <a class="back-link" href="manage_cases.php">← Back to Case Management</a>
    </div>
</body>
</html>
