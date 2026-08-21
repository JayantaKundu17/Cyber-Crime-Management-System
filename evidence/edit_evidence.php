<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$evidence_id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $case_id = $_POST['case_id'];
    $description = $_POST['description'];

    $upload_path = $_POST['existing_path'];
    if (isset($_FILES['evidence_file']) && $_FILES['evidence_file']['error'] === 0) {
        $filename = basename($_FILES['evidence_file']['name']);
        $target_dir = "uploads/";
        $upload_path = $target_dir . uniqid() . "_" . $filename;
        move_uploaded_file($_FILES['evidence_file']['tmp_name'], $upload_path);
    }

    $stmt = $conn->prepare("UPDATE evidence SET case_id = ?, description = ?, file_path = ? WHERE evidence_id = ?");
    $stmt->bind_param("issi", $case_id, $description, $upload_path, $evidence_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_evidence.php");
    exit();
}

$evidence = $conn->query("SELECT * FROM evidence WHERE evidence_id = $evidence_id")->fetch_assoc();
$cases = $conn->query("SELECT case_id, title FROM cases");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Evidence</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: #333;
            padding: 50px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        select, textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        textarea {
            height: 100px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            font-size: 16px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #1abc9c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Evidence</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Case:</label>
            <select name="case_id" required>
                <?php while ($row = $cases->fetch_assoc()): ?>
                    <option value="<?= $row['case_id'] ?>" <?= $row['case_id'] == $evidence['case_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($evidence['description']) ?></textarea>

            <label>Replace File (optional):</label>
            <input type="file" name="evidence_file">
            <input type="hidden" name="existing_path" value="<?= $evidence['file_path'] ?>">

            <button class="submit-btn" type="submit">Update Evidence</button>
        </form>
    </div>
</body>
</html>
