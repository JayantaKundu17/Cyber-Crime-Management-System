<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET['id']);

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $name, $email, $role, $user_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}

// Fetch existing user
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-box {
            background: #ffffff10;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ecf0f1;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #f39c12;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #ecf0f1;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <form class="form-box" method="POST" action="">
        <h2>Edit User</h2>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="Administrator" <?= $user['role'] === 'Administrator' ? 'selected' : '' ?>>Administrator</option>
            <option value="Officer" <?= $user['role'] === 'Officer' ? 'selected' : '' ?>>Officer</option>
        </select>
        <button type="submit">Update User</button>
        <a href="manage_users.php"> Back to User List</a>
    </form>
</body>
</html>