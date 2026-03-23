<?php
require 'UserModel.php';
require 'Bank.php';
require_once __DIR__ . '/../config/db.php'; // Include the database connection
class DoneeModel extends UserModel
{
    private string $nationalID;
    private BankType $bank;
    private string $proofOfCaseDocument;
    private bool $isVerified;

    private bool $isOrganization;

    public function __construct()
    {
        parent::__construct();
    }

    // file path to the document 
    // (may be hashed/encrypted) example "uploads/proof_of_case/12313123242553959695946784964.pdf"
    // TODO: implement file upload and storage logic in the controller, 
    // and ensure that the file path is correctly set in this property when a donee registers.


    // Getters and Setters

    // National ID
    public function setNationalID(string $nationalID): void
    {
        $this->nationalID = $nationalID;
    }
    function getNationalID(): string
    {
        return $this->nationalID;
    }

    // Bank
    public function setBank(BankType $bank): void
    {
        $this->bank = $bank;
    }

    public function getBank(): BankType
    {
        return $this->bank;
    }

    // Proof of Case Document
    public function setProofOfCaseDocument(string $proofOfCaseDocument): void
    {
        $this->proofOfCaseDocument = $proofOfCaseDocument;
    }

    public function getProofOfCaseDocument(): string
    {
        return $this->proofOfCaseDocument;
    }
    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }
    public function getIsVerified(): bool
    {
        return $this->isVerified;
    }
    public function setIsOrganization(bool $isOrganization): void
    {
        $this->isOrganization = $isOrganization;
    }
    public function getIsOrganization(): bool
    {
        return $this->isOrganization;
    }
    public function setDonee(
        $id,
        $fname,
        $lname,
        $email,
        $password,
        $phone,
        string $nationalID,
        BankType $bank,
        string $proofOfCaseDocument = ""
    ): self {
        parent::setUser($id, $fname, $lname, $email, $password, $phone);
        $this->nationalID = $nationalID;
        $this->bank = $bank;
        $this->proofOfCaseDocument = $proofOfCaseDocument;
        return $this;
    }

    public function getDonee($email, $password): self|false
    {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
        $stmt = getDatabaseConnection()->prepare("
    SELECT * FROM user 
    INNER JOIN donees ON user.user_id = donees.user_id
    WHERE user.user_email = ?
");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["user_password"])) {
                $donee = new self();
                return $donee->setDonee(
                    $row["user_id"],
                    $row["user_fname"],
                    $row["user_lname"],
                    $row["user_email"],
                    $row["user_password"],
                    $row["user_phone"],
                    $row["donee_national_id"],
                    BankType::from($row["donee_bank_name"]),
                    $row["donee_proof_of_case_document"]
                );
            }
        }
        return false;
    }

    /* this method retrieves all donees from the database, 
    creates DoneeModel objects for each record, and returns them as an array. 
    If no donees are found, it returns false.
    */
    #[\Override]
    public function getAllUsers(): array|false
    {
        $doneeArray = array();
        $stmt = getDatabaseConnection()->prepare("
    SELECT * FROM user 
    INNER JOIN donees ON user.user_id = donees.user_id
");
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $donee = new self();
            $donee->setDonee(
                $row["user_id"],
                $row["user_fname"],
                $row["user_lname"],
                $row["user_email"],
                $row["user_password"],
                $row["user_phone"],
                $row["donee_national_id"],
                BankType::from($row["donee_bank_name"]),
                $row["donee_proof_of_case_document"]
            );
            $doneeArray[] = $donee;
        }

        return !empty($doneeArray) ? $doneeArray : false;
    }

    #[\Override]
    public function updateUser($id, $fname, $lname, $email, $phone, $password): bool
    {
        parent::updateUser($id, $fname, $lname, $email, $phone, $password);
        /**
         * since we can't pass bankname directly like this ```$this->bank->getBankName()```
         * because $this->bank is an instance of BankType enum, 
         * we need to call the getBankName() method to retrieve 
         * the string value
         * This is necessary because the database expects a string value for the bank name,
         * not an enum instance.
         * If we were to pass $this->bank directly, it would likely cause an error 
         */
        $bankname = $this->bank->getBankName();
        $stmt = getDatabaseConnection()->prepare("UPDATE donees SET 
        donee_national_id = ?, donee_bank_name = ?, donee_proof_of_case_document = ?
        WHERE user_id = ?");
        /*
         * we bind the parameters for the prepared statemnt using the bind_param method
         * the first argument is a string that specifies the types of the parameters:
         * 
         * 
         */
        $stmt->bind_param("sssi", $this->nationalID, $bankname, $this->proofOfCaseDocument, $id);
        $stmt->execute();

        return $stmt->affected_rows >= 0;
    }

   public function register(): bool
{
    
    $fname    = $this->getFname();
    $lname    = $this->getLname();
    $email    = $this->getEmail();
    $password = $this->getPassword();
    $phone    = $this->getPhone();

    
    $stmt = getDatabaseConnection()->prepare("
        INSERT INTO user (user_fname, user_lname, user_email, user_password, user_phone)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssss", $fname, $lname, $email, $password, $phone);
    $stmt->execute();
    $userId = $stmt->insert_id;

    $bankName = $this->bank->getBankName();
    $stmt = getDatabaseConnection()->prepare("
        INSERT INTO donees (user_id, donee_national_id, donee_bank_name, donee_proof_of_case_document)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $userId, $this->nationalID, $bankName, $this->proofOfCaseDocument);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}
}