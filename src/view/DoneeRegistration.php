<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../model/Bank.php'; // Include the enum definition
use BankType;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donee Registration</title>
</head>
<body>
    <h1>Donee Registration</h1>
    
    <form action="register_donee.php" method="post" enctype="multipart/form-data">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required>
        <br><br>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required>
        <br><br>

        <label for="DOB">Date of Birth:</label>
        <input type="date" id="DOB" name="DOB" required>
        <br><br>

        <label for="nationalID">National ID:</label>
        <input type="text" id="nationalID" name="nationalID" required>
        <br><br>

        <label for="bank">Bank:</label>
        <select id="bank" name="bank" required>
            <?php foreach (BankType::cases() as $bank): ?>
                <option value="<?= $bank->name ?>"><?= $bank->name ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="proofOfCaseDocument">Proof of Case Document:</label>
        <input type="file" id="proofOfCaseDocument" name="proofOfCaseDocument" accept=".pdf,.jpg,.jpeg,.png" required>
        <br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>