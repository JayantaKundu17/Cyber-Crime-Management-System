<?php

$host = ""; // Enter your MySQL database host/server address here
$username = ""; // Enter your MySQL database username here
$password = ""; // Enter your MySQL database password here
$database = ""; // Enter your MySQL database name here

$port = 3306;// Enter your MySQL port number here (3306 is the standard MySQL port)

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
