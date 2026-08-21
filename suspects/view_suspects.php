<?php
include '../config.php';

$result = $conn->query("SELECT * FROM Suspects");
?>

<h2>List of Suspects</h2>
<table border="1">
    <tr>
        <th>Suspect ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Address</th>
        <th>Crime Associated (Case ID)</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['suspect_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['crime_associated'] ?></td>
            <td>
                <a href="edit_suspect.php?id=<?= $row['suspect_id'] ?>">Edit</a> | 
                <a href="delete_suspect.php?id=<?= $row['suspect_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
<a href="add_suspect.php">Add New Suspect</a>