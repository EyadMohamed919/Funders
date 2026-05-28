<?php
require_once __DIR__ . "/IAuthStrategy.php";
require_once __DIR__ . "/../UserModel.php";
require_once __DIR__ . "/../../../../config/db.php";

class EmailPasswordAuth implements IAuthStrategy{
	public function authenticate($contactValue, $password)
	{
		$conn = getDatabaseConnection();
		$contactValue = $conn->real_escape_string(trim($contactValue));
		$sql = $conn->query("SELECT user_id FROM user_contacts WHERE contact_type = 'email' AND contact_value = '$contactValue' LIMIT 1");
		if(!$sql || $sql->num_rows == 0)
		{
			return null;
		}
		$row = $sql->fetch_assoc();
		$userID = (int) $row["user_id"];

		$userModel = new UserModel();
		$user = $userModel->getUserByID($userID);
		if(!$user)
		{
			return null;
		}
		if(!password_verify($password, $user["password_hash"]))
		{
			return null;
		}

		return $userID;
	}
}

