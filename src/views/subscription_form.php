<?php
if (!isset($isEdit))
{
    $isEdit = false;
}

if (!isset($subscription))
{
    $subscription = null;
}

if (!isset($error))
{
    $error = "";
}
?>

<h1>
<?php if($isEdit): ?>
    Edit Subscription #<?php echo $subscription["subscription_id"]; ?>
<?php else: ?>
    Create Subscription
<?php endif; ?>
</h1>

<?php if($error !== ""): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php
if($isEdit)
{
    $formAction = "subscription.php?action=edit&id=" .
                  $subscription["subscription_id"];
}
else
{
    $formAction = "subscriptions.php?action=create";
}
?>

<form method="post" action="<?php echo $formAction; ?>">

    <?php if(!$isEdit): ?>
        <p>
            User ID:
            <input type="number" name="user_id" required>
        </p>
    <?php endif; ?>

    <p>
        Status:
        <select name="status">

            <option value="active"
                <?php
                if($isEdit && $subscription["status"] == "active")
                {
                    echo "selected";
                }
                ?>>
                active
            </option>

            <option value="pending"
                <?php
                if($isEdit && $subscription["status"] == "pending")
                {
                    echo "selected";
                }
                ?>>
                pending
            </option>

            <option value="cancelled"
                <?php
                if($isEdit && $subscription["status"] == "cancelled")
                {
                    echo "selected";
                }
                ?>>
                cancelled
            </option>

        </select>
    </p>

    <p>
        Amount:
        <input
            type="text"
            name="amount"
            value="<?php
                if($isEdit)
                {
                    echo $subscription["amount"];
                }
            ?>"
            required
        >
    </p>

    <p>
        Frequency:
        <input
            type="text"
            name="frequency"
            value="<?php
                if($isEdit)
                {
                    echo htmlspecialchars($subscription["frequency"]);
                }
                else
                {
                    echo 'monthly';
                }
            ?>"
        >
    </p>

    <p>
        Gateway ID:
        <input
            type="text"
            name="gateway_id"
            value="<?php
                if($isEdit)
                {
                    echo htmlspecialchars($subscription["gateway_id"]);
                }
            ?>"
        >
    </p>

    <?php if(!$isEdit): ?>
        <p>
            Start Date:
            <input type="datetime-local" name="start_date">
        </p>
    <?php endif; ?>

    <h3>Custom Attributes</h3>

    <?php if($isEdit && !empty($subscription["custom_attributes"])): ?>

        <p><strong>Edit existing:</strong></p>

        <?php foreach($subscription["custom_attributes"] as $attr): ?>

            <p>
                Attribute <?php echo $attr["attribute_id"]; ?>:

                <input
                    type="text"
                    name="custom_field[<?php echo $attr["attribute_id"]; ?>]"
                    value="<?php echo htmlspecialchars($attr["value"]); ?>"
                >
            </p>

        <?php endforeach; ?>

    <?php endif; ?>

    <p><strong>Add new:</strong></p>

    <p>
        Attribute ID:
        <input
            type="number"
            name="custom_field[new_id]"
            placeholder="e.g. 7"
        >
    </p>

    <p>
        Value:
        <input
            type="text"
            name="custom_field[new_value]"
            placeholder="value"
        >
    </p>

    <button type="submit">
        <?php if($isEdit): ?>
            Save Changes
        <?php else: ?>
            Create
        <?php endif; ?>
    </button>

    <?php if($isEdit): ?>
        <a href="subscription.php?action=show&id=<?php echo $subscription["subscription_id"]; ?>">
            Cancel
        </a>
    <?php endif; ?>

</form>