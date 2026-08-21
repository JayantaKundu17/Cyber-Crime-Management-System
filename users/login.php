<?php
session_start();

include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        die("Email and password are required.");
    }

    // Use lowercase table name: users
    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Login query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        die("Login execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {

            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role'] = $row['role'];

            // Record login in audit log
            $action = "Logged in";

            $log_stmt = $conn->prepare(
                "INSERT INTO audit_log (user_id, action, timestamp)
                 VALUES (?, ?, NOW())"
            );

            if ($log_stmt) {
                $log_stmt->bind_param(
                    "is",
                    $row['user_id'],
                    $action
                );
                $log_stmt->execute();
                $log_stmt->close();
            }

            // Redirect to the PHP dashboard
            header("Location: ../index.php");
            exit();

        } else {
            $error = "Incorrect password!";
        }

    } else {
        $error = "User not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cyber Crime Portal</title>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 400px;
            background: #fff;
            padding: 30px;
            margin: 100px auto;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background-color: #2980b9;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2471a3;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>

<body>

<div class="login-container">

    <h2>Login</h2>

    <?php if (isset($error)): ?>
        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">Login</button>

    </form>

</div>

</body>
</html>