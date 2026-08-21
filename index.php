<?php
session_start();

// If the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

// Redirect based on user role
$dashboard_url = ($_SESSION['role'] === 'Administrator') ? "admin/admin_dashboard.php" : "officers/officer_dashboard.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cyber Crime Management</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #2c3e50, #3498db);
      color: #fff;
      text-align: center;
      padding-top: 60px;
    }

    .container {
      background-color: white;
      color: #333;
      margin: 0 auto;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
      max-width: 600px;
    }

    img.logo {
      width: 100px;
      margin-bottom: 20px;
    }

    h1 {
      margin-bottom: 10px;
    }

    a button {
      margin-top: 20px;
      padding: 12px 24px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    a button:hover {
      background-color: #2980b9;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="assets/cyber-crime-logo.png" alt="Cyber Crime Logo" class="logo">
    <h1>Welcome to Cyber Crime Management System</h1>
    <p>Click below to go to your dashboard:</p>
    <a href="<?php echo $dashboard_url; ?>"><button>Go to Dashboard</button></a>
  </div>
</body>
</html>
