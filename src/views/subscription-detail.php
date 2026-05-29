<?php
// Use project lightweight autoloader so we don't depend on Composer's autoload
require_once __DIR__ . '/../autoload.php';

use App\Db\Database;
use App\Controllers\SubscriptionController;

try {
    $db = Database::connect();
} catch (Exception $e) {
    die('DB connection error: ' . $e->getMessage());
}

$ctrl = new SubscriptionController($db);

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo 'Invalid subscription id';
    exit;
}

$subscription = $ctrl->getById($id);
if (!$subscription) {
    http_response_code(404);
    echo 'Subscription not found';
    exit;
}

$entities = $ctrl->getEntities($id);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Subscription #<?php echo (int)$subscription['subscription_id']; ?></title>
    <link rel="stylesheet" href="/Funders/public/css/admin.css">
</head>
<body>
<div class="container">
    <header class="page-header">
        <h1>Subscription #<?php echo (int)$subscription['subscription_id']; ?></h1>
    </header>

    <div class="page-content">
        <div class="detail-panel">
            <h3>Basic Information</h3>
            <div class="detail-grid">
                <div class="detail-item"><label>Status</label><div><?php echo htmlspecialchars($subscription['status']); ?></div></div>
                <div class="detail-item"><label>Amount</label><div>$<?php echo number_format((float)$subscription['amount'], 2); ?></div></div>
                <div class="detail-item"><label>Frequency</label><div><?php echo htmlspecialchars($subscription['frequency']); ?></div></div>
                <div class="detail-item"><label>Start Date</label><div><?php echo htmlspecialchars($subscription['start_date']); ?></div></div>
                <div class="detail-item"><label>Gateway ID</label><div><?php echo htmlspecialchars($subscription['gateway_id'] ?? ''); ?></div></div>
            </div>
        </div>

        <div class="detail-panel">
            <h3>Entities (<?php echo count($entities); ?>)</h3>
            <?php if (!empty($entities)): ?>
                <?php foreach ($entities as $entity): ?>
                    <div class="entity-card">
                        <div class="entity-header"><h4>Entity #<?php echo (int)$entity['entity_id']; ?></h4></div>
                        <?php if (!empty($entity['attributes'])): ?>
                            <div class="attributes-list">
                                <?php
                                    $attrs = explode('|', $entity['attributes']);
                                    foreach ($attrs as $attr) {
                                        $parts = explode(':', $attr, 2);
                                        if (count($parts) === 2) {
                                            echo '<div class="attribute-item"><strong>' . htmlspecialchars($parts[0]) . '</strong>: ' . htmlspecialchars($parts[1]) . '</div>';
                                        }
                                    }
                                ?>
                            </div>
                        <?php else: ?>
                            <div class="text-muted">No attributes assigned.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">No entities for this subscription.</div>
            <?php endif; ?>
        </div>

        <p><a href="subscription-list.php">Back to list</a></p>
    </div>
</div>
</body>
</html>
