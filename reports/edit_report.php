<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// ✅ Validate and sanitize GET parameter
if (!isset($_GET['id'])) {
    die("Report ID not provided.");
}
$report_id = intval($_GET['id']);

// ✅ Fetch the report
$stmt = $conn->prepare("SELECT * FROM reports WHERE report_id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();
$stmt->close();

if (!$report) {
    die("Report not found.");
}

// ✅ Officer access control
if ($role === 'Officer' && $report['officer_id'] != $user_id) {
    die("Access denied.");
}

// ✅ On form submit, update the report
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $case_id = $_POST['case_id'];
    $report_details = $_POST['report_details'];

    $update_stmt = $conn->prepare("UPDATE reports SET case_id = ?, report_details = ? WHERE report_id = ?");
    $update_stmt->bind_param("isi", $case_id, $report_details, $report_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Log action
    $action = "Edited report ID: $report_id";
    $log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
    $log_stmt->bind_param("is", $user_id, $action);
    $log_stmt->execute();
    $log_stmt->close();

    header("Location: reports.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Report</title>
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

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #bdc3c7;
        }

        button {
            background-color: #2980b9;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2471a3;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #7f8c8d;
            color: white;
            padding: 10px 14px;
            border-radius: 6px;
        }

        .back-btn:hover {
            background-color: #616a6b;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Report</h2>
        <form method="post">
            <label>Case ID:</label>
            <input type="number" name="case_id" value="<?= htmlspecialchars($report['case_id']) ?>" required>

            <label>Report Details:</label>
            <textarea name="report_details" rows="5" required><?= htmlspecialchars($report['report_details']) ?></textarea>

            <button type="submit">Update Report</button>
        </form>

        <a class="back-btn" href="reports.php">← Back to Reports</a>
    </div>
</body>
</html>
