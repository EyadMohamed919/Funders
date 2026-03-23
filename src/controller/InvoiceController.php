<?php
require_once("../model/InvoiceModel.php");

class InvoiceController {
    private $InvoiceModel;

    public function __construct() {
        $this->InvoiceModel = new InvoiceModel();
    }

    public function getInvoice() {
        if (isset($_GET['invoice_id'])) {
            $invoice = $this->InvoiceModel->getInvoiceById($_GET['invoice_id']);
            if ($invoice) {
                return $invoice;
            }
            http_response_code(404);
            return ['error' => 'Invoice not found'];
        }

        if (isset($_GET['user_id'])) {
            return $this->InvoiceModel->getInvoicesByUser($_GET['user_id']);
        }

        return $this->InvoiceModel->getAllInvoices();
    }

    public function addInvoice() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        if (empty($_POST['user_id']) || empty($_POST['amount']) || empty($_POST['status']) || empty($_POST['due_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'user_id, amount, status and due_date are required']);
            return;
        }

        $success = $this->InvoiceModel->addInvoice(
            $_POST['user_id'],
            $_POST['amount'],
            $_POST['status'],
            $_POST['due_date']
        );

        if ($success) {
            http_response_code(201);
            echo json_encode(['message' => 'Invoice created']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create invoice']);
        }
    }

    public function updateInvoice() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        if (empty($_POST['invoice_id']) || empty($_POST['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'invoice_id and status are required']);
            return;
        }

        $success = $this->InvoiceModel->updateInvoiceStatus($_POST['invoice_id'], $_POST['status']);

        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Invoice updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update invoice']);
        }
    }

    public function deleteInvoice() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        if (empty($_POST['invoice_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'invoice_id is required']);
            return;
        }

        $success = $this->InvoiceModel->deleteInvoice($_POST['invoice_id']);

        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Invoice deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete invoice']);
        }
    }
}
