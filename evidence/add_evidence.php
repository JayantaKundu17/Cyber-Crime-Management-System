<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = $_POST['case_id'];
    $description = $_POST['description'];

    $upload_path = '';
    if (isset($_FILES['evidence_file']) && $_FILES['evidence_file']['error'] === 0) {
        $filename = basename($_FILES['evidence_file']['name']);
        $target_dir = "uploads/";
        $upload_path = $target_dir . uniqid() . "_" . $filename;
        move_uploaded_file($_FILES['evidence_file']['tmp_name'], $upload_path);
    }

    $stmt = $conn->prepare("INSERT INTO evidence (case_id, description, file_path, upload_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $case_id, $description, $upload_path);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_evidence.php");
    exit();
}

$cases = $conn->query("SELECT case_id, title FROM cases");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Evidence</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #2c3e50;
            margin: 0;
            padding: 50px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            color: #34495e;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
        }

        select, textarea, input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            display: block;
            width: 100%;
            margin-top: 30px;
            background-color: #2980b9;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1abc9c;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Evidence</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="case_id">Case:</label>
            <select name="case_id" id="case_id" required>
                <?php while ($row = $cases->fetch_assoc()): ?>
                    <option value="<?= $row['case_id'] ?>"><?= htmlspecialchars($row['title']) ?></option>
                <?php endwhile; ?>
            </select>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="evidence_file">Upload File:</label>
            <input type="file" name="evidence_file" id="evidence_file" required>

            <button type="submit">Add Evidence</button>
        </form>
    </div>
</body>
</html>
