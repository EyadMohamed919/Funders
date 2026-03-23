<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../controller/DonationController.php";

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
        if($_POST["router"] == "create")
        {
            $controller->addDonation();
        }
        else if ($_POST["router"] == "delete") {
            $controller->deleteDonation();
        }
        else
        {
            $controller->updateDonation();
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}