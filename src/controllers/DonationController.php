<?php 
require_once __DIR__ . "/../models/DonationTypes.php";
class DonationController{
    public static function getAllDonationTypes()
    {
        $donationTypes = new DonationTypes();
        $types = $donationTypes->getAllDonationTypes();
        return $types;
    }
}