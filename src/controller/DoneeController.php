<?php
require_once("../model/DoneeModel.php");

class DoneeController extends UserController
{

    #[\override]
    public static function login()
    {
        $email = $_POST['email'] ?? '';
        $pass = $_POST['password'] ?? '';

        $DoneeModel = new DoneeModel();
        $userData = $DoneeModel->getDonee($email, $pass);

        if ($userData) {
            session_start();
            $_SESSION['user_id'] = $userData->getId();
            $_SESSION['user_fname'] = $userData->getFname();
            $_SESSION['user_lname'] = $userData->getLname();
            $_SESSION['user_email'] = $userData->getEmail();
            $_SESSION['user_phone'] = $userData->getPhone();
            $_SESSION['LOGIN_ERROR'] = null;

            header("Location: /dashboard");
            exit();
        } else {
            session_start();
            $_SESSION['LOGIN_ERROR'] = "Incorrect email or password";
            header("location:../view/layout/login.php");
        }
    }
    
// TODO: not implemented updateDonee() method for the Donee
    // private static function uploadProofOfCase(): string
    // {
    //     $file = $_FILES['proof_of_case_document'];

    //     if ($file['error'] !== UPLOAD_ERR_OK) {
    //         throw new Exception("File upload failed.");
    //     }

    //     $filename = hash('sha256', time() . $_SESSION['user_fname']) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    //     $path = "uploads/{$_SESSION['user_id']}/proofofcase/{$filename}";
    //     $absolutePath = __DIR__ . "/../../{$path}";

    //     mkdir(dirname($absolutePath), 0700, true);
    //     move_uploaded_file($file['tmp_name'], $absolutePath);

    //     return $path;
 //   }
}