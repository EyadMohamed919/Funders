<?php
header("Content-Type: application/json");
require_once("../../controllers/DonationController.php");
$controller = new DonationController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['user_id'])) {
            echo json_encode($controller->getDonation());
        } else {
            echo json_encode($controller->getAllDonation());
        }
        break;
    case 'POST':
        $controller->addDonation();
        break;
    case 'PUT':
        $controller->updateDonation();
        break;
    case 'DELETE':
        $controller->deleteDonation();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}