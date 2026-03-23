<?php
header("Content-Type: application/json");
require_once("../../controller/InvoiceController.php");

$controller = new InvoiceController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        echo json_encode($controller->getInvoice());
        break;

    case 'POST':
        if (!isset($_POST['router'])) {
            http_response_code(400);
            echo json_encode(['error' => 'router action required (create|update|delete)']);
            break;
        }

        switch ($_POST['router']) {
            case 'create':
                $controller->addInvoice();
                break;
            case 'update':
                $controller->updateInvoice();
                break;
            case 'delete':
                $controller->deleteInvoice();
                break;
            default:
                http_response_code(400);
                echo json_encode(['error' => 'Unsupported router action']);
                break;
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
