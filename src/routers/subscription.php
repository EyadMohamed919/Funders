<?php
require_once __DIR__ . "/../controllers/SubscriptionController.php";

$controller = new SubscriptionController();

if (isset($_GET["action"]))
{
    $action = $_GET["action"];
}
else
{
    $action = "show";
}

if (isset($_GET["id"]))
{
    $id = intval($_GET["id"]);
}
else
{
    $id = 0;
}

switch ($action)
{
    case "show":
        $controller->show($id);
        break;

    case "edit":
        $controller->edit($id);
        break;

    case "destroy":
        $controller->destroy($id);
        break;

    default:
        $controller->show($id);
        break;
}
?>

