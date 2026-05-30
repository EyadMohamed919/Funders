<?php
// Receives: $subscription (array from getSubscriptionByID)
?>
<h1>Delete Subscription #<?php echo $subscription["subscription_id"]; ?>?</h1>

<table border="1" cellpadding="5" cellspacing="0">
    <tr><td>User ID</td><td><?php echo $subscription["user_id"]; ?></td></tr>
    <tr><td>Status</td><td><?php echo $subscription["status"]; ?></td></tr>
    <tr><td>Amount</td><td>$<?php echo $subscription["amount"]; ?></td></tr>
    <tr><td>Frequency</td><td><?php echo $subscription["frequency"]; ?></td></tr>
</table>

<br><form method="post" action="/router/subscription.php?action=destroy&id=<?php echo $subscription["subscription_id"]; ?>">
        <button type="submit" onclick="return confirm('Are you sure? This cannot be undone.')">Yes, Delete</button>
    <a href="/router/subscription.php?id=<?php echo $subscription["subscription_id"]; ?>">Cancel</a>
</form>