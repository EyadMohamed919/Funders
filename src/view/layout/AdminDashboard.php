<?php
session_start();


require_once __DIR__ . "/../../model/AdminModel.php";
require_once __DIR__ . "/../../controller/AdminController.php";
require_once __DIR__ . "/../../controller/PostController.php";
$admin = new AdminController();
$data = PostController::getAllPosts();

$checkadmin = $admin->checkAdmin($_SESSION['user_id']);
if($checkadmin == 0)
{
    header("location: /src/view/layout/Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../../public/css/adminDashboardStyle.css">
</head>

<body>
<a href="AdminManagement.php" class="btn btn-primary">Manage Admins</a>
<section class="ftco-section">
    <div class="container">
        <h4 class="text-center mb-4">Post Table</h4>

        <div class="table-wrap">
            <table class="table">
                <thead class="thead-primary">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Current Amount</th>
                        <th>Target Amount</th>
                        <th>Status</th>
                        <th>Decision</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $row): ?>
                    
                    <tr>
                        <td><?php echo $row->getId(); ?></td>
                        <td><?php echo $row->getTitle(); ?></td>
                        <td><?php echo $row->getCalculateAmount($row->getId()); ?></td>
                        <td><?php echo $row->getTargetAmount(); ?></td>
                        <td><?php echo $row->getStatus(); ?></td>
                        <td>
                        <?php 
                            if($row->getStatus() == "active")
                            { ?>
                                <a class="btn btn-danger" href="../../router/PostRouter.php?status=rejected&id=<?php echo $row->getId(); ?>">Reject</a>
                            <?php }
                            else
                            { ?>
                                <a class="btn btn-primary" href="../../router/PostRouter.php?status=active&id=<?php echo $row->getId(); ?>">Approve</a>
                                <?php } 
                        ?>
                            
                            
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <a href="../../router/UserRouter.php?logout=true">Logout</a>
    </div>
</section>
</body>
</html>