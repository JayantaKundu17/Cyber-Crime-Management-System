<?php
include '../config.php';

if (isset($_GET['id'])) {
    $suspect_id = $_GET['id'];
    $result = $conn->query("SELECT * FROM Suspects WHERE suspect_id = $suspect_id");
    $suspect = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $crime_associated = $_POST['crime_associated'];

    $sql = "UPDATE Suspects SET name='$name', age='$age', gender='$gender', 
            address='$address', crime_associated='$crime_associated' WHERE suspect_id = $suspect_id";

    if ($conn->query($sql) === TRUE) {
        echo "Suspect updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="post">
    Name: <input type="text" name="name" value="<?= $suspect['name'] ?>" required><br>
    Age: <input type="number" name="age" value="<?= $suspect['age'] ?>"><br>
    Gender: 
    <select name="gender">
        <option value="Male" <?= ($suspect['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
        <option value="Female" <?= ($suspect['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
        <option value="Other" <?= ($suspect['gender'] == 'Other') ? 'selected' : '' ?>>Other</option>
    </select><br>
    Address: <textarea name="address"><?= $suspect['address'] ?></textarea><br>
    Associated Case ID: <input type="number" name="crime_associated" value="<?= $suspect['crime_associated'] ?>" required><br>
    <input type="submit" value="Update Suspect">
</form>