<?php
require_once __DIR__ . "/PostController.php";

$controller = new PostController();
$action = $_GET["action"] ?? "index";

switch ($action) {
    case "show":
        $id = intval($_GET["id"] ?? 0);
        $controller->show($id);
        break;
    
    case "admin":
        $controller->admin();
        break;
    
    case "index":
    default:
        $controller->index();
        break;
}