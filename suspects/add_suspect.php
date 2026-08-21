<?php
include '../config.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $crime_associated = $_POST['crime_associated'];

    $sql = "INSERT INTO Suspects (name, age, gender, address, crime_associated) 
            VALUES ('$name', '$age', '$gender', '$address', '$crime_associated')";

    if ($conn->query($sql) === TRUE) {
        echo "Suspect added successfully.";
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
    Address: <textarea name="address"></textarea><br>
    Associated Case ID: <input type="number" name="crime_associated" required><br>
    <input type="submit" value="Add Suspect">
</form>