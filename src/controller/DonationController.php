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
        $success= $this->DonationModel->addDonation(
            $_POST['amount'], 
            $date,
            $_POST['id'],
            $_SESSION['user_id']); 

        if ($success) {
            echo "Success";
        } else {
        }
    }

    public function updateDonation() {
    $_POST = json_decode(file_get_contents("php://input"), true);

    if (empty($_POST['donation_id']) || empty($_POST['donation_amount']) || 
        empty($_POST['donation_date'])  || empty($_POST['user_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        return;
    }
    
    $success = $this->DonationModel->UpdateDonation(
        $_POST['donation_id'],
        $_POST['donation_amount'],
        $_POST['donation_date'],
        $_POST['user_id']
    );
    if ($success) {
            http_response_code(201);
            echo json_encode(['message' => 'Donation updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update donation']);
        }
    }

    public function deleteDonation(){
        $_POST= json_decode(file_get_contents('php://input'), true);
        if (empty($_POST['donation_id'] || empty($_POST['user_id']))) {
            http_response_code(400);
            echo json_encode(['error' => 'donation_id and user_id are required']);
            return;
        }
        $success = $this->DonationModel->deleteDonation(
            $_POST['donation_id'], 
            $_POST['user_id']);
        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Donation deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete donation']);
        }
    }
}