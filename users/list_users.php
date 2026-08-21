<?php
include '../db_connect.php';

$sql = "SELECT * FROM Users";
$result = $conn->query($sql);
?>

<h2>User List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $row['user_id']; ?>">Edit</a> |
                <a href="delete_user.php?id=<?php echo $row['user_id']; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>