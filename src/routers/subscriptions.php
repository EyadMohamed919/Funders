<?php
require_once __DIR__ . "/../controllers/SubscriptionController.php";

echo "Received id: " . ($_GET["id"] ?? "NONE") . "<br>";
echo "Received action: " . ($_GET["action"] ?? "NONE") . "<br><hr>";
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