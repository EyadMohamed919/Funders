<?php
require_once __DIR__ . "/../controllers/DonationController.php";
require_once __DIR__ . "/../models/Donation/DonationModel.php";
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

    public static function fetchMyDonatedTypes($userID)
    {
        $donationModel = new DonationController();
        $donationArray = $donationModel->getAllDonationByUserID($userID);
        echo "<table class='table'>";
            echo "<thead>";
                echo "<tr>";
                    echo "
                    <th>ID</th>
                    <th>PostID</th>
                    <th>Donation Type</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach($donationArray as $donation)
            {
                echo "<tr>";
                    echo "
                    <td>" . $donation->getDonationID() . "</td>
                    <td>" . $donation->getPostID() . "</td>
                    <td>" . $donation->getTypeName() . "</td>   
                    ";
                echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";
    }
}
