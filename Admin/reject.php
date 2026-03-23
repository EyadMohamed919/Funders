<?php
$conn = new mysqli("localhost", "root", "", "funders");

$id = $_GET['id'];

$conn->query("UPDATE funders SET Status=0 WHERE SubscriptionID=$id");
$conn->query("DELETE FROM funders WHERE SubscriptionID=$id");


header("Location: dashboard.php");
?>