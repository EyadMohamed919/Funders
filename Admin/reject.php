<?php
$conn = new mysqli("localhost", "root", "", "funders");

$id = $_GET['id'];
$conn->query("UPDATE subscriptions SET status='rejected' WHERE SubscriptionID=$id");

header("Location: dashboard.php");
?>