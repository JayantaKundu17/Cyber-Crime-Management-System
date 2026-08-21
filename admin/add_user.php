<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header("Location: ../users/login.php");
    exit();
}

include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add User</title>
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
            background: #1abc9c;
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
        <h2>Add New User</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="">Select Role</option>
            <option value="Administrator">Administrator</option>
            <option value="Officer">Officer</option>
        </select>
        <button type="submit">Add User</button>
        <a href="manage_users.php">? Back to User List</a>
    </form>
</body>
</html>