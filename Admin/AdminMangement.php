<?php

require_once "adminModel.php";
$model = new Model();

?>

<table border="1">
<tr>
    <th>Username</th>
    <th>Action</th>
</tr>

<?php
$admins = $model->getAdmins();
foreach ($admins as $admin):
?>
<tr>
    <td><?php echo $admin['Username']; ?></td>
    <td>
        <a href="delete_admin.php?username=<?php echo $admin['Username']; ?>">
            Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>


</table>
<h4>Add Admin</h4>
<form method="POST" action="add_admin.php">
    <input type="text" name="username" required>
    <input type="text" name="password" required>
    <button type="submit">Add</button>
</form>

<a href="MainAdminPage.php">Return to Main Page</a>