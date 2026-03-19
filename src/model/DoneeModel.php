<?php
require 'UserModel.php';
require 'Bank.php';
require_once __DIR__ . '/../config/db.php'; // Include the database connection
class DoneeModel extends UserModel
{
    private string $nationalID;
    private BankType $bank;
    private bool $isVerified;
    private string $proofOfCaseDocument;

    // file path to the document 
    // (may be hashed/encrypted) example "uploads/proof_of_case/12313123242553959695946784964.pdf"
    // TODO: implement file upload and storage logic in the controller, 
    // and ensure that the file path is correctly set in this property when a donee registers.


    // Getters and Setters

    // National ID
    function setNationalID(string $nationalID): void
    {
        $this->nationalID = $nationalID;
    }
    function getNationalID(): string
    {
        return $this->nationalID;
    }

    // Bank
    function setBank(BankType $bank): void
    {
        $this->bank = $bank;
    }

    function getBank(): BankType
    {
        return $this->bank;
    }

    // Is Verified
    function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }
    function getIsVerified(): bool
    {
        return $this->isVerified;
    }

    // Proof of Case Document
    function setProofOfCaseDocument(string $proofOfCaseDocument): void
    {
        $this->proofOfCaseDocument = $proofOfCaseDocument;
    }

    function getProofOfCaseDocument(): string
    {
        return $this->proofOfCaseDocument;
    }

    function uploadID()
    {
        $conn = $this->connect();
    //    $sql = "INSERT INTO donees () VALUES ()";


    }

}