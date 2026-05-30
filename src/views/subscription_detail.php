<?php
// Defensive defaults
if (!isset($subscription)) $subscription = null;
?>
<h1>Subscription #<?php echo isset($subscription["subscription_id"]) ? $subscription["subscription_id"] : "Not Found"; ?></h1>

<?php if ($subscription === null): ?>
    <p style="color:red;">Subscription not found.</p>
    <a href="subscriptions.php">Back to List</a>
<?php else: ?>

<table border="1" cellpadding="5" cellspacing="0">
    <tr><td>User ID</td><td><?php echo $subscription["user_id"]; ?></td></tr>
    <tr><td>Status</td><td><?php echo $subscription["status"]; ?></td></tr>
    <tr><td>Amount</td><td>$<?php echo $subscription["amount"]; ?></td></tr>
    <tr><td>Frequency</td><td><?php echo $subscription["frequency"]; ?></td></tr>
    <tr><td>Gateway</td><td><?php echo $subscription["gateway_id"]; ?></td></tr>
    <tr><td>Start Date</td><td><?php echo $subscription["start_date"]; ?></td></tr>
    <tr><td>Next Billing</td><td><?php echo $subscription["next_billing_date"]; ?></td></tr>
    <tr><td>Created At</td><td><?php echo $subscription["created_at"]; ?></td></tr>
    <?php if(!empty($subscription["subscriptionscol"])): ?>
    <tr><td>SubscriptionsCol</td><td><?php echo $subscription["subscriptionscol"]; ?></td></tr>
    <?php endif; ?>
</table>

<h2>Custom Attributes</h2>
<?php if(!empty($subscription["custom_attributes"])): ?>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr><th>Attribute ID</th><th>Value</th></tr>
        <?php foreach($subscription["custom_attributes"] as $attr): ?>
        <tr>
            <td><?php echo $attr["attribute_id"]; ?></td>
            <td><?php echo htmlspecialchars($attr["value"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No custom attributes.</p>
<?php endif; ?>

<br>
<a href="/router/subscription.php?action=edit&id=<?php echo $subscription["subscription_id"]; ?>">Edit</a>

<form method="post" action="/router/subscription.php?action=destroy&id=<?php echo $subscription["subscription_id"]; ?>" style="display:inline;">
    <button type="submit">Delete</button>
</form>

<a href="/router/subscriptions.php">Back to List</a>

<?php endif; ?>