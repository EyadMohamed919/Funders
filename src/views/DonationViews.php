<?php
require_once __DIR__ . "/../controllers/DonationController.php";

class DonationViews{
    public static function fetchAllDonationTypes()
    {
        $donationTypesArray = DonationController::getAllDonationTypes();
        echo "<select name='donationType' id='donation_type' class='donation-select'>";
        echo "<option selected disabled>Select Donation Type</option>";
        foreach ($donationTypesArray as $id => $name) {
            echo "<option value='" . $id . "'>" . $name . "</option>";
        }
        echo "</select>";
    }
}
