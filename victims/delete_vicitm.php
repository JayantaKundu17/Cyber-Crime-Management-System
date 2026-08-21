<?php
include '../config.php'; // Include the database connection

if (isset($_GET['id'])) {
    $victim_id = $_GET['id'];

    $sql = "DELETE FROM Victims WHERE victim_id = $victim_id";

    if ($conn->query($sql) === TRUE) {
        echo "Victim deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<a href="list_victims.php">Go Back</a>