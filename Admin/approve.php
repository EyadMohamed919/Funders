<?php
$conn = new mysqli("localhost", "root", "", "funders");

$id = $_GET['id'];
$conn->query("UPDATE subscriptions SET status='approved' WHERE SubscriptionID=$id");

header("Location: dashboard.php");
?>