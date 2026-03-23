<?php 
require_once __DIR__ . "/../model/InvoiceModel.php";
require_once __DIR__ . "/../model/DonationModel.php";
class InvoiceController{
    public function createInvoice($donationID)
    {
        session_start();
        $donation = new DonationModel();
        $donationObj = $donation->getDonationByDonationID($donationID);
        $invoice = new InvoiceModel($_SESSION["user_id"], $donationObj->getDonationAmount(), $donationObj->getDonationDate());
        include("../view/layout/Invoice.php");
        $invoice->getAmount
    }
}

if(isset($_GET))
{
    if(isset($_GET["id"]))
    {
        $invoiceController = new InvoiceController();
        $invoiceController->createInvoice($_GET["id"]);
    }
}


?>