<?php
require_once __DIR__ . "/../controllers/DonationController.php";

class DonationViews{
    public static function fetchAllDonationTypes()
    {
        $donationTypesArray = DonationController::getAllDonationTypes();
        echo "<select name='donationType'>";
        foreach ($donationTypesArray as $id => $name) {
            echo "<option value='" . $id . "'>" . $name . "</option>";
        }
        echo "</select>";
    }
}
