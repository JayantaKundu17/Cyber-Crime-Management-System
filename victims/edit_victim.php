<?php
include '../config.php'; // Include the database connection

if (isset($_GET['id'])) {
    $victim_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM Victims WHERE victim_id = $victim_id");
    $victim = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $case_id = $_POST['case_id'];

    $sql = "UPDATE Victims SET name='$name', age='$age', gender='$gender', 
            contact='$contact', address='$address', case_id='$case_id' 
            WHERE victim_id = $victim_id";

    if ($conn->query($sql) === TRUE) {
        echo "Victim updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post">
    Name: <input type="text" name="name" value="<?= $victim['name'] ?>" required><br>
    Age: <input type="number" name="age" value="<?= $victim['age'] ?>"><br>
    Gender: 
    <select name="gender">
        <option value="Male" <?= ($victim['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= ($victim['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= ($victim['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
    </select><br>
    Contact: <input type="text" name="contact" value="<?= $victim['contact'] ?>"><br>
    Address: <textarea name="address"><?= $victim['address'] ?></textarea><br>
    Case ID: <input type="number" name="case_id" value="<?= $victim['case_id'] ?>" required><br>
    <input type="submit" value="Update Victim">
</form>