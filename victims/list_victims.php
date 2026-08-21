<?php
include '../config.php'; // Include the database connection

$result = $conn->query("SELECT * FROM Victims");
?>

<h2>List of Victims</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Contact</th>
        <th>Address</th>
        <th>Case ID</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['victim_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['age'] ?></td>
            <td><?= $row['gender'] ?></td>
            <td><?= $row['contact'] ?></td>
            <td><?= $row['address'] ?></td>
            <td><?= $row['case_id'] ?></td>
            <td>
                <a href="edit_victim.php?id=<?= $row['victim_id'] ?>">Edit</a> | 
                <a href="delete_victim.php?id=<?= $row['victim_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
<a href="add_victim.php">Add New Victim</a>