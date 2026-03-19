<?php
require 'UserModel.php';
require 'Bank.php';

class DoneeModel extends UserModel {
    private string $nationalID;
    private BankType $bank;
    private bool $isVerified;
    private string $proofOfCaseDocument; // file path to the document (may be hashed/encrypted)

    
}

