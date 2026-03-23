<?php

require_once __DIR__ . "/../../controller/AdminController.php";
$admin = new AdminController();

?>

<table border="1">
<tr>
    <th>Username</th>
    <th>Action</th>
</tr>

<?php
$admins = $admin->getAllAdmin();
foreach ($admins as $admin):
?>
<tr>
    <td><?php echo $admin['user_fname'] . " " . $admin['user_lname']; ?></td>
    <td>
        <a href="delete_admin.php?username=<?php echo $admin['user_id'] ?>">
            Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>


</table>
<h4>Add Admin</h4>
<form method="POST" action="add_admin.php">
    <input type="text" name="fname" required>
    <input type="text" name="lname" required>
    <input type="text" name="email" required>
    <input type="number" name="fname" required>
    <input type="password" name="password" required>
    <button type="submit">Add</button>
</form>

<a href="MainAdminPage.php">Return to Main Page</a>