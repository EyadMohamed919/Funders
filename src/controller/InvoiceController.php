<?php
require_once __DIR__ . "/../model/InvoiceModel.php";

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
        if (empty($_POST['user_id']) || empty($_POST['amount']) || empty($_POST['status']) || empty($_POST['due_date'])) {
            http_response_code(400);
            echo json_encode(['error' => 'user_id, amount, status and due_date are required']);
            return;
        }

        // Check if user exists
        $stmt = getDatabaseConnection()->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $_POST['user_id']);
        $stmt->execute();
        if ($stmt->get_result()->num_rows == 0) {
            http_response_code(400);
            echo json_encode(['error' => 'User does not exist']);
            return;
        }

        $success = $this->InvoiceModel->addInvoice(
            $_POST['user_id'],
            $_POST['amount'],
            $_POST['status'],
            $_POST['due_date']
        );

        if ($success) {
            header("Location: /src/view/layout/invoice.php");
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create invoice']);
        }
    }

    public function updateInvoice() {
        if (empty($_POST['invoice_id']) || empty($_POST['status'])) {
            http_response_code(400);
            echo json_encode(['error' => 'invoice_id and status are required']);
            return;
        }

        $success = $this->InvoiceModel->updateInvoiceStatus($_POST['invoice_id'], $_POST['status']);

        if ($success) {
            header("Location: /src/view/layout/invoice.php");
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update invoice']);
        }
    }

    public function deleteInvoice() {
        if (empty($_POST['invoice_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'invoice_id is required']);
            return;
        }

        $success = $this->InvoiceModel->deleteInvoice($_POST['invoice_id']);

        if ($success) {
            header("Location: /src/view/layout/invoice.php");
            exit;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete invoice']);
        }
    }
}
