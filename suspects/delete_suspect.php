<?php
include '../config.php';

if (isset($_GET['id'])) {
    $suspect_id = $_GET['id'];

    $sql = "DELETE FROM Suspects WHERE suspect_id = $suspect_id";

    if ($conn->query($sql) === TRUE) {
        echo "Suspect deleted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<a href="list_suspects.php">Go Back</a>