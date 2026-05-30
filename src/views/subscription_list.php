
<h1>All Subscriptions</h1>
<p><a href="subscription_create.php">+ Create New Subscription</a></p>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>User ID</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Frequency</th>
        <th>Gateway</th>
        <th>Actions</th>
    </tr>
    <?php foreach($subscriptions as $sub): ?>
    <tr>
        <td><?php echo $sub["subscription_id"]; ?></td>
        <td><?php echo $sub["user_id"]; ?></td>
        <td><?php echo $sub["status"]; ?></td>
        <td>$<?php echo $sub["amount"]; ?></td>
        <td><?php echo $sub["frequency"]; ?></td>
        <td><?php echo $sub["gateway_id"]; ?></td>
<td>
<a href="/router/subscription.php?action=show&id=<?php echo $sub["subscription_id"]; ?>">View</a>
<a href="/router/subscription.php?action=edit&id=<?php echo $sub["subscription_id"]; ?>">Edit</a>
</td>
    </tr>
    <?php endforeach; ?>
</table>