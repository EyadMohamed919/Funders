<?php 
require_once __DIR__ . "../../../config/db.php";

class InvoiceModel{
    private $invoiceNumber;
    private $amount;
    private $date;
    private $userID;

    public function __construct($userID, $amount, $date)
    {
        $this->date = $date;
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO invoice(user_id, invoice_amount, invoice_date) 
        VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $userID, $amount, $this->date);
        $stmt->execute();
        $this->amount = $amount;
        $this->invoiceNumber = $conn->insert_id;
    }

    public function setInvoice($userID, $invoiceNumber, $amount, $date)
    {
        $this->invoiceNumber = $invoiceNumber; 
        $this->userID = $userID;
        $this->amount = $amount;
        $this->date = $date;
    }

    public function getInvoice($id)
    {
        $stmt = getDatabaseConnection()->prepare("SELECT * FROM invoice WHERE invoice_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {
            $row = $result->fetch_assoc();
            $this->setInvoice($row["user_id"], $id, $row["invoice_amount"], $row["invoice_date"]);
            return $this;
        }
    }

    public function getInvoiceNumber(){return $this->invoiceNumber;}
    public function getAmount(){return $this->amount;}
    public function getDate(){return $this->date;}
}
?>