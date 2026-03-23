<?php 
require_once __DIR__ . "../../../config/db.php";

class InvoiceModel{
    private $invoiceNumber;
    private $amount;
    private $date;

    public function __construct($userID, $amount, $date)
    {
        $date = new DateTime();
        $date->format('Y-m-d');
        $this->date = $date;
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO invoice(invoice_amount, invoice_date, invoice_date) 
        VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $amount, $date);
        $stmt->execute();
        $this->amount = $amount;
        $this->date = $date;
        $this->invoiceNumber = $conn->insert_id;
    }

    public function getInvoiceNumber(){return $this->invoiceNumber;}
    public function getAmount(){return $this->amount;}
    public function getDate(){return $this->date;}
}
?>