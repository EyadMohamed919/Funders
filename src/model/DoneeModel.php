    <?php
    require 'UserModel.php';
    require 'Bank.php';
    require_once __DIR__ . '/../config/db.php'; // Include the database connection
    class DoneeModel extends UserModel
    {
        private string $nationalID;
        private BankType $bank;
        private string $proofOfCaseDocument;

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

        // public function uploadID()
        // {
        //     $conn = $this->connect();
        //     //    $sql = "INSERT INTO donees () VALUES ()";


        // }
  public function setDonee($id, $fname, $lname, $email, $password, $phone,
    string $nationalID, BankType $bank, string $proofOfCaseDocument = ""): self
{
    parent::setUser($id, $fname, $lname, $email, $password, $phone);
    $this->nationalID = $nationalID;
    $this->bank = $bank;
    $this->proofOfCaseDocument = $proofOfCaseDocument;
    return $this;
}


public function getDonee($email, $password)
{
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $stmt = getDatabaseConnection()->prepare("SELECT * FROM donees WHERE user_email = ?");
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
                $row["national_id"],
                BankType::{$row["bank_name"]},
                $row["proof_of_case_document"]
            );
        }
    }
    return false;
}


        // functions related to doneemodel here

#[\Override]
public function getAllUsers(): array
{
    $doneeArray = array();
    $stmt = getDatabaseConnection()->prepare("SELECT * FROM donees");
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
            $row["national_id"],
            BankType::{$row["bank_name"]},
            $row["proof_of_case_document"]
        );
        $doneeArray[] = $donee;
    }

    return !empty($doneeArray) ? $doneeArray : false;
}

#[\Override]
public function updateUser($id, $fname, $lname, $email, $phone, $password): bool
{
    parent::updateUser($id, $fname, $lname, $email, $phone, $password);

    $stmt = getDatabaseConnection()->prepare("UPDATE donees SET 
        national_id = ?, bank_name = ?, proof_of_case_document = ?
        WHERE user_id = ?");

    $stmt->bind_param("sssi", $this->nationalID, $this->bank->name, $this->proofOfCaseDocument, $id);
    $stmt->execute();

    return $stmt->affected_rows >= 0;
}
}