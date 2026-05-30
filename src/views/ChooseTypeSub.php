<!DOCTYPE html>
<html lang="en">
<head>
    <title>Subscription Form</title>
</head>
<body>
<?php
$choice = $_POST['choice'] ?? '';
?>
<form method="POST">

    <label>
        <input
            type="radio"
            name="choice"
            value="one-time"
            onchange="this.form.submit()"
            <?php if ($choice === 'one-time') echo 'checked'; ?>
        >
        One-time
    </label>
    <br><br>

<?php if ($choice === 'one-time'): ?>
    <label for="amount">Amount:</label>
    <input
        type="number"
        id="amount"
        name="amount"
        min="1"
        placeholder="Enter amount"
        list="amount-suggestions"
    >
    <datalist id="amount-suggestions">
        <option value="10">$10</option>
        <option value="20">$20</option>
        <option value="50">$50</option>
    </datalist>
    <br><br>
<?php endif; ?>

    <label>
        <input
            type="radio"
            name="choice"
            value="recurring"
            onchange="this.form.submit()"
            <?php if ($choice === 'recurring') echo 'checked'; ?>
        >
        Recurring
    </label>
    <br><br>

    <?php if ($choice === 'recurring'): ?>
        <label for="frequency">Frequency:</label>
        <select name="frequency" id="frequency">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select>
        <br><br>
    <?php endif; ?>

    <button type="submit">Submit</button>

</form>
</body>
</html>