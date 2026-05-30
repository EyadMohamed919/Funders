<?php
require_once __DIR__ . "/../controllers/SubscriptionController.php";

$controller = new SubscriptionController();
$action = $_GET["action"] ?? "show";

switch ($action) {
    case "show":
        $id = intval($_GET["id"] ?? 0);
        $controller->show($id);
        break;

    case "edit":
        $id = intval($_GET["id"] ?? 0);
        $controller->edit($id);
        break;

    case "destroy":
        $id = intval($_GET["id"] ?? 0);
        $controller->destroy($id);
        break;

    default:
        $id = intval($_GET["id"] ?? 0);
        $controller->show($id);
        break;
}
?>