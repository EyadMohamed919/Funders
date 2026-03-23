<?php 
require_once __DIR__ . "/../model/InvoiceModel.php";
require_once __DIR__ . "/../model/DonationModel.php";
require_once __DIR__ . "/../model/UserModel.php";
class InvoiceController{
    public function createInvoice($donationID)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $donation = new DonationModel();
        $userModel = new UserModel();
        $user = $userModel->getUserByID($_SESSION["user_id"]);
        $donationObj = $donation->getDonationByDonationID($donationID);

        $invoice = new InvoiceModel($_SESSION["user_id"], $donationObj->getDonationAmount(), $donationObj->getDonationDate());
        include("../view/layout/Invoice.php");
        
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