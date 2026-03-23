<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../controller/InvoiceController.php";

$controller = new InvoiceController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['router'])) {
            switch ($_GET['router']) {
                case 'update':
                    if (!isset($_GET['invoice_id']) || !isset($_GET['status'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'invoice_id and status required']);
                        break 2;
                    }
                    $_POST['invoice_id'] = $_GET['invoice_id'];
                    $_POST['status'] = $_GET['status'];
                    $controller->updateInvoice();
                    header("Location: /src/view/layout/invoice.php");
                    exit;
                case 'delete':
                    if (!isset($_GET['invoice_id'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'invoice_id required']);
                        break 2;
                    }
                    $_POST['invoice_id'] = $_GET['invoice_id'];
                    $controller->deleteInvoice();
                    header("Location: /src/view/layout/invoice.php");
                    exit;
                default:
                    http_response_code(400);
                    echo json_encode(['error' => 'Unsupported router action']);
                    break 2;
            }
        } else {
            echo json_encode($controller->getInvoice());
        }
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
