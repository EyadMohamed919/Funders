<?php
require_once __DIR__ . "/../controllers/SubscriptionController.php";

$controller = new SubscriptionController();
$action = $_GET["action"] ?? "index";

switch ($action) {
    case "create":
        $controller->create();
        break;

    case "index":
    default:
        $controller->index();
        break;
}
?>