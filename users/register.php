<?php
include '../db_connect.php'; // Fix the path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Use Prepared Statements for security
    $stmt = $conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "User registered successfully!";
        header("Location: login.php"); // Redirect to login after registration
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!-- Registration Form -->
<form method="POST" action="register.php">
    <input type="text" name="name" placeholder="Enter Name" required><br>
    <input type="email" name="email" placeholder="Enter Email" required><br>
    <input type="password" name="password" placeholder="Enter Password" required><br>
    <select name="role">
        <option value="Officer">Officer</option>
        <option value="Administrator">Administrator</option>
    </select><br>
    <button type="submit">Register</button>
</form>
