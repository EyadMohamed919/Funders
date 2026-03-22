<?php
require_once("../model/DonationModel.php");
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
        $data= json_decode(file_get_contents('php://input'), true);
        if (empty($data['user_id']) || empty($data['donation_amount'])|| empty($data['donation_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'donation_id, user_id, or donation_amount is missing']);
            return;
        }
        $success= $this->DonationModel->addDonation(
            $data['user_id'], 
            $data['donation_amount'], 
            $data['donation_id']);
        if ($success) {
            http_response_code(201);
            echo json_encode(['message' => 'Donation added successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to add donation']);
        }
    }

    public function updateDonation() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['donation_id']) || empty($data['donation_amount']) || 
        empty($data['donation_date'])  || empty($data['user_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        return;
    }

    $success = $this->DonationModel->UpdateDonation(
        $data['donation_id'],
        $data['donation_amount'],
        $data['donation_date'],
        $data['user_id']
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
        $data= json_decode(file_get_contents('php://input'), true);
        if (empty($data['donation_id'] || empty($data['user_id']))) {
            http_response_code(400);
            echo json_encode(['error' => 'donation_id and user_id are required']);
            return;
        }
        $success = $this->DonationModel->deleteDonation(
            $data['donation_id'], 
            $data['user_id']);
        if ($success) {
            http_response_code(200);
            echo json_encode(['message' => 'Donation deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete donation']);
        }
    }
}