<?php
// Defensive defaults — prevents "Undefined variable" warnings
if (!isset($isEdit)) $isEdit = false;
if (!isset($subscription)) $subscription = null;
if (!isset($error)) $error = "";
?>
<h1><?php echo $isEdit ? "Edit Subscription #" . $subscription["subscription_id"] : "Create Subscription"; ?></h1>

<?php if($error !== ""): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php
$formAction = $isEdit 
    ? "/router/subscription.php?action=edit&id=" . $subscription["subscription_id"] 
    : "/router/subscriptions.php?action=create";
?>

<form method="post" action="<?php echo $formAction; ?>">

    <?php if(!$isEdit): ?>
        <p>User ID: <input type="number" name="user_id" required></p>
    <?php endif; ?>

    <p>Status:
        <select name="status">
            <option value="active" <?php echo ($isEdit && $subscription["status"] == "active") ? "selected" : ""; ?>>active</option>
            <option value="pending" <?php echo ($isEdit && $subscription["status"] == "pending") ? "selected" : ""; ?>>pending</option>
            <option value="cancelled" <?php echo ($isEdit && $subscription["status"] == "cancelled") ? "selected" : ""; ?>>cancelled</option>
        </select>
    </p>

    <p>Amount: <input type="text" name="amount" value="<?php echo $isEdit ? $subscription["amount"] : ""; ?>" required></p>
    <p>Frequency: <input type="text" name="frequency" value="<?php echo $isEdit ? htmlspecialchars($subscription["frequency"]) : "monthly"; ?>"></p>
    <p>Gateway ID: <input type="text" name="gateway_id" value="<?php echo $isEdit ? htmlspecialchars($subscription["gateway_id"]) : ""; ?>"></p>

    <?php if(!$isEdit): ?>
        <p>Start Date: <input type="datetime-local" name="start_date"></p>
    <?php endif; ?>

    <h3>Custom Attributes</h3>

    <?php if($isEdit && !empty($subscription["custom_attributes"])): ?>
        <p><strong>Edit existing:</strong></p>
        <?php foreach($subscription["custom_attributes"] as $attr): ?>
            <p>
                Attribute <?php echo $attr["attribute_id"]; ?>:
                <input type="text" name="custom_field[<?php echo $attr["attribute_id"]; ?>]" value="<?php echo htmlspecialchars($attr["value"]); ?>">
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><strong>Add new:</strong></p>
    <p>Attribute ID: <input type="number" name="custom_field[new_id]" placeholder="e.g. 7"></p>
    <p>Value: <input type="text" name="custom_field[new_value]" placeholder="value"></p>

    <button type="submit"><?php echo $isEdit ? "Save Changes" : "Create"; ?></button>
    <a href="<?php echo $isEdit ? "/router/subscription.php?action=show&id=" . $subscription["subscription_id"] : "/router/subscriptions.php"; ?>">Cancel</a>
</form>