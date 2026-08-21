<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $case_id = $_POST['case_id'];
    $report_details = $_POST['report_details'];
    $report_date = $_POST['report_date'];
    $officer_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO reports (case_id, report_details, officer_id, report_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $case_id, $report_details, $officer_id, $report_date);
    $stmt->execute();

    // ?? Log the report creation
    $action = "Added a new report (Case ID: $case_id)";
    $log_stmt = $conn->prepare("INSERT INTO audit_log (user_id, action) VALUES (?, ?)");
    $log_stmt->bind_param("is", $officer_id, $action);
    $log_stmt->execute();

    header("Location: reports.php"); // Updated from view_reports.php
    exit();
}

// Fetch cases assigned to this officer
$officer_id = $_SESSION['user_id'];
$cases = $conn->query("SELECT case_id, title FROM cases WHERE officer_id = $officer_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Report</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: white;
            padding: 40px;
        }
        .form-container {
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
        }
        input, textarea, select {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            border: none;
        }
        button {
            background-color: #1abc9c;
            padding: 12px;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        button:hover {
            background-color: #16a085;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add New Report</h2>
        <form method="POST">
            <select name="case_id" required>
                <option value="">Select Case</option>
                <?php while ($row = $cases->fetch_assoc()): ?>
                    <option value="<?= $row['case_id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
                <?php endwhile; ?>
            </select>

            <textarea name="report_details" placeholder="Report Details" rows="5" required></textarea>
            <input type="date" name="report_date" required>
            <button type="submit">Submit Report</button>
        </form>
    </div>
</body>
</html>