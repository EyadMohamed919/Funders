<?php
require_once __DIR__ . "/../model/DonationModel.php";
class DonationController {
    private $DonationModel;

    public function __construct(){
        $this->DonationModel = new DonationModel();
    }

    public function getDonation(){
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
        if (!$user_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing user_id parameter']);
            return;
        }
        return $this->DonationModel->getDonation($user_id);
    }

    public function getAllDonation(){
        $donation= $this->DonationModel->getAllDonation();
        return $donation;
    }

    public function addDonation(){
        session_start();
        $date = new DateTime();
        $date = $date->format('Y-m-d');
        $donationID= $this->DonationModel->addDonation(
            $_POST['amount'], 
            $date,
            $_POST['id'],
            $_SESSION['user_id']); 

        if ($donationID != 0) {
            header("location: ../view/donation/success.php?id=" . $donationID);
        } else {
            echo "Failed to donate";
        }
    }


}