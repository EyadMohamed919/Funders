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
        <a href="../../router/AdminRouter.php?delete=true&id=<?php echo $admin['user_id'] ?>">
            Delete
        </a>
    </td>
</tr>
<?php endforeach; ?>


</table>
<h4>Add Admin</h4>
<form method="POST" action="../../router/AdminRouter.php">
    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="lname" placeholder="Last Name" required>
    <input type="text" name="email" placeholder="Email" required>
    <input type="text" name="router" value="add" required hidden>
    <input type="number" name="phone" placeholder="Phone" required>
    <input type="password" name="password" placeholder="*****8" required>
    <button type="submit">Add</button>
</form>

<a href="MainAdminPage.php">Return to Main Page</a>