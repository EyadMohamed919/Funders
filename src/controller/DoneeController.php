<?php
require_once("../model/DoneeModel.php");

class DoneeController extends UserController
{
    public static function update()
    {
        $donee = new DoneeModel();
        $donee->getDonee($_SESSION['user_email'], $_POST['password']); // load existing donee

        $donee->setNationalID($_POST['national_id']);
        $donee->setBank(BankType::from($_POST['bank']));
        $donee->setProofOfCaseDocument($_POST['proof_of_case_document']);

        $donee->updateUser(
            $_SESSION['user_id'],
            $_POST['fname'],
            $_POST['lname'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['password']
        );
    }
}