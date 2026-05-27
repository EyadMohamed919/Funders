<?php
require_once __DIR__ . "/../models/Donation/DonationModel.php";
require_once __DIR__ . "/../models/Donation/DonationTypes.php";
require_once __DIR__ . "/../models/Donation/DonationMoneyStrategy.php";
require_once __DIR__ . "/../models/Donation/IDonationStrategy.php";
class InvoiceController
{
    public IDonationStrategy $donation;
    public $DonationTypeName;

    public function __construct($donationID)
    {
        $this->prepareInvoiceByDonationID($donationID);
    }

    public function prepareInvoiceByDonationID($donationID)
    {
        $donation = new DonationModel();
        $donation->getDonationByDonationID($donationID);
        $donationTypeID = $donation->getDonationType();
        switch ($donationTypeID) {
            case 1:
                $donation = new DonationMoneyStrategy();
                $this->donation = $donation;
                $donationTypeModel = new DonationTypes();
                $this->DonationTypeName = $donationTypeModel->getDonationTypeName($donationTypeID);

                break;
            
            default:
                break;
        }
    }
}