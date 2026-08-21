<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if (!isset($_GET['case_id'])) {
    die("Invalid case ID.");
}

$case_id = intval($_GET['case_id']);

// Fetch existing case data
$stmt = $conn->prepare("SELECT * FROM cases WHERE case_id = ?");
$stmt->bind_param("i", $case_id);
$stmt->execute();
$result = $stmt->get_result();
$case = $result->fetch_assoc();
$stmt->close();

if (!$case) {
    die("Case not found.");
}

// 🛡️ Access control check
if ($role !== 'Administrator' && $case['officer_id'] != $user_id) {
    die("Access denied. You can only edit your own cases.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    if ($role === 'Administrator') {
        $officer_id = $_POST['officer_id'];
    } else {
        $officer_id = $user_id; // Officer can only assign case to themselves
    }

    $stmt = $conn->prepare("UPDATE cases SET title = ?, description = ?, status = ?, officer_id = ? WHERE case_id = ?");
    $stmt->bind_param("sssii", $title, $description, $status, $officer_id, $case_id);
    $stmt->execute();
    $stmt->close();

    // Log the update
    $action = "Updated case ID: $case_id";
    $log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
    $log_stmt->bind_param("is", $user_id, $action);
    $log_stmt->execute();
    $log_stmt->close();

    header("Location: manage_cases.php");
    exit();
}

// Fetch list of officers for admin dropdown
if ($role === 'Administrator') {
    $officers = $conn->query("SELECT user_id, name FROM users WHERE role = 'Officer'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Case</title>
    <style>
        body {
            background: linear-gradient(to right, #2c3e50, #3498db);
            font-family: Arial, sans-serif;
            padding: 50px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #bdc3c7;
        }

        button {
            background-color: #27ae60;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #219150;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            padding: 10px 15px;
            background-color: #2980b9;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #2471a3;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Case</h2>
    <form method="post">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($case['title']) ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($case['description']) ?></textarea>

        <label>Status:</label>
        <select name="status">
            <option value="Open" <?= $case['status'] === 'Open' ? 'selected' : '' ?>>Open</option>
            <option value="Under Investigation" <?= $case['status'] === 'Under Investigation' ? 'selected' : '' ?>>Under Investigation</option>
            <option value="Closed" <?= $case['status'] === 'Closed' ? 'selected' : '' ?>>Closed</option>
        </select>

        <?php if ($role === 'Administrator'): ?>
            <label>Assign Officer:</label>
            <select name="officer_id">
                <?php while ($row = $officers->fetch_assoc()): ?>
                    <option value="<?= $row['user_id'] ?>" <?= $row['user_id'] == $case['officer_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        <?php endif; ?>

        <button type="submit">Update Case</button>
    </form>

    <a href="manage_cases.php" class="back-btn">
        ← Back to Case List
    </a>
</div>
</body>
</html>
