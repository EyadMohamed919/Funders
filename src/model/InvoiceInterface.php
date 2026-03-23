<?php
interface InvoiceInterface {
    public function getInvoiceId();
    public function getUserId();
    public function getAmount();
    public function getStatus();
    public function getCreatedAt();
    public function getDueDate();

    public function setInvoiceId($invoice_id);
    public function setUserId($user_id);
    public function setAmount($amount);
    public function setStatus($status);
    public function setCreatedAt($created_at);
    public function setDueDate($due_date);
}
