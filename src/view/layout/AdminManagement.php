<?php

require_once __DIR__ . "/../../controller/AdminController.php";
$admin = new AdminController();

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <h1>Admin List</h1>
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
        <input type="text" name="router" value="add" style="display: none;" required hidden>
        <input type="number" name="phone" placeholder="Phone" required>
        <input type="password" name="password" placeholder="*****" required>
        <button type="submit">Add</button>
    </form>

    <a href="MainAdminPage.php">Return to Main Page</a>
</body>
</html>
