<?php
session_start();
require_once "adminModel.php";

$model = new Model();
$data = $model->getAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<a href="AdminMangement.php" class="btn btn-primary">Manage Admins</a>
<section class="ftco-section">
    <div class="container">
        <h4 class="text-center mb-4">Administration Table</h4>

        <div class="table-wrap">
            <table class="table">
                <thead class="thead-primary">
                    <tr>
                        <th>Subscription ID</th>
                        <th>Document URL</th>
                        <th>TimeStamp</th>
                        <th>Decision</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo $row['SubscriptionID']; ?></td>
                        <td><?php echo $row['DocumentURL']; ?></td>
                        <td><?php echo $row['TimeStamp']; ?></td>
                        <td>
                            <a class="btn btn-primary" href="approve.php?id=<?php echo $row['SubscriptionID']; ?>">Approve</a>
                            <a class="btn btn-danger" href="reject.php?id=<?php echo $row['SubscriptionID']; ?>">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <a href="MainAdminPage.php">Logout</a>
    </div>
</section>
</body>
</html>