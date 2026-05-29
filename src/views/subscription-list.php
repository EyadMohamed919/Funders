<?php
require __DIR__ . "/../../config/db.php";
require __DIR__ . "/../controllers/SubscriptionController.php";

$conn = getDatabaseConnection();
if (!$conn) {
    die('DB connection error: mysqli connection not available.');
}

$subscriptionController = new App\Controllers\SubscriptionController($conn);

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = $_GET['search'] ?? null;
$status = $_GET['status'] ?? null;

$subscriptions = $subscriptionController->getAll($page, 20, $search, $status);
$totalCount = $subscriptionController->getCount($search, $status);
$totalPages = max(1, (int) ceil($totalCount / 20));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Subscriptions</title>
    <link rel="stylesheet" href="/Funders/public/css/admin.css">
</head>
<body>
<div class="container">
    <header class="page-header">
        <h1>Subscriptions</h1>
    </header>

    <div class="page-content">
        <div class="action-bar">
            <div class="search-form">
                <form method="get" action="">
                    <input type="text" name="search" placeholder="Search subscriptions..." value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES); ?>">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </form>
            </div>
            <div class="filter-bar">
                <a href="subscription-list.php" class="filter-btn">All</a>
                <a href="subscription-list.php?status=active" class="filter-btn">Active</a>
                <a href="subscription-list.php?status=paused" class="filter-btn">Paused</a>
                <a href="subscription-list.php?status=cancelled" class="filter-btn">Cancelled</a>
                <a href="subscription-list.php?status=pending" class="filter-btn">Pending</a>
            </div>
        </div>

        <div class="table-container">
            <?php if (!empty($subscriptions)): ?>
            <table class="data-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Amount</th>
                    <th>Frequency</th>
                    <th>Status</th>
                    <th>Start Date</th>
                    <th>Created</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($subscriptions as $sub): ?>
                    <tr>
                        <td><?php echo (int) $sub['subscription_id']; ?></td>
                        <td>$<?php echo number_format((float) ($sub['amount'] ?? 0), 2); ?></td>
                        <td><?php echo htmlspecialchars($sub['frequency'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($sub['status'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($sub['start_date'] ?? ''); ?></td>
                        <td><?php echo isset($sub['creation_date']) ? date('Y-m-d', (int)$sub['creation_date']) : '-'; ?></td>
                        <td><a href="subscription-detail.php?id=<?php echo (int)$sub['subscription_id']; ?>">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=1" class="page-link">« First</a>
                        <a href="?page=<?php echo $page-1; ?>" class="page-link">‹ Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page+1; ?>" class="page-link">Next ›</a>
                        <a href="?page=<?php echo $totalPages; ?>" class="page-link">Last »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php else: ?>
                <div class="empty-state">No subscriptions found.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
