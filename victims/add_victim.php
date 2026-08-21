<?php
include '../config.php'; // Include the database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $case_id = $_POST['case_id'];

    // Check if the case exists before inserting
    $check_case = $conn->query("SELECT case_id FROM Cases WHERE case_id = '$case_id'");
    if ($check_case->num_rows == 0) {
        die("Error: Case ID does not exist.");
    }

    // Insert the victim into the database
    $sql = "INSERT INTO Victims (name, age, gender, contact, address, case_id) 
            VALUES ('$name', '$age', '$gender', '$contact', '$address', '$case_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Victim added successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post">
    Name: <input type="text" name="name" required><br>
    Age: <input type="number" name="age"><br>
    Gender: 
    <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select><br>
    Contact: <input type="text" name="contact"><br>
    Address: <textarea name="address"></textarea><br>
    Case ID: <input type="number" name="case_id" required><br>
    <input type="submit" value="Add Victim">
</form>