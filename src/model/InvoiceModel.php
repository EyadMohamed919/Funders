<?php
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/InvoiceInterface.php";

class InvoiceModel implements InvoiceInterface {
    public $invoice_id;
    public $user_id;
    public $amount;
    public $status;
    public $created_at;
    public $due_date;

    public function getInvoiceId() { return $this->invoice_id; }
    public function getUserId() { return $this->user_id; }
    public function getAmount() { return $this->amount; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }
    public function getDueDate() { return $this->due_date; }

    public function setInvoiceId($invoice_id) { $this->invoice_id = htmlspecialchars($invoice_id); }
    public function setUserId($user_id) { $this->user_id = htmlspecialchars($user_id); }
    public function setAmount($amount) { $this->amount = htmlspecialchars($amount); }
    public function setStatus($status) { $this->status = htmlspecialchars($status); }
    public function setCreatedAt($created_at) { $this->created_at = htmlspecialchars($created_at); }
    public function setDueDate($due_date) { $this->due_date = htmlspecialchars($due_date); }

    public function setInvoice($invoice_id, $user_id, $amount, $status, $created_at, $due_date) {
        $this->setInvoiceId($invoice_id);
        $this->setUserId($user_id);
        $this->setAmount($amount);
        $this->setStatus($status);
        $this->setCreatedAt($created_at);
        $this->setDueDate($due_date);
        return $this;
    }

    public function getInvoiceById($invoice_id) {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM invoice WHERE invoice_id = ?");
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getInvoicesByUser($user_id) {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM invoice WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllInvoices() {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM invoice");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addInvoice($user_id, $amount, $status, $due_date) {
        $created_at = date('Y-m-d H:i:s');
        $stmt = getDatabaseConnection()->prepare("INSERT INTO invoice (user_id, invoice_amount, invoice_status, invoice_created_at, invoice_due_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idsss", $user_id, $amount, $status, $created_at, $due_date);
        return $stmt->execute();
    }

    public function updateInvoiceStatus($invoice_id, $status) {
        $stmt = getDatabaseConnection()->prepare("UPDATE invoice SET invoice_status = ? WHERE invoice_id = ?");
        $stmt->bind_param("si", $status, $invoice_id);
        return $stmt->execute();
    }

    public function deleteInvoice($invoice_id) {
        $stmt = getDatabaseConnection()->prepare("DELETE FROM invoice WHERE invoice_id = ?");
        $stmt->bind_param("i", $invoice_id);
        return $stmt->execute();
    }
}
