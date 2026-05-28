<?php
require_once __DIR__ . "/../models/User/UserModel.php";
require_once __DIR__ . "/../models/User/Auth/EmailPasswordAuth.php";
require_once __DIR__ . "/../models/User/Auth/PhonePasswordAuth.php";
require_once __DIR__ . "/../models/User/Roles/BaseUserRole.php";
require_once __DIR__ . "/../models/User/Roles/DonorRoleDecorator.php";
require_once __DIR__ . "/../models/User/Roles/DoneeRoleDecorator.php";
require_once __DIR__ . "/../models/User/Roles/AdminRoleDecorator.php";
class UserController{
	public static function register()
	{
		if($_SERVER["REQUEST_METHOD"] != "POST")
		{
			return;
		}
		$fullName = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
		$password = isset($_POST["password"]) ? $_POST["password"] : "";
		$contactType = isset($_POST["contact_type"]) ? $_POST["contact_type"] : "email";
		$contactValue = isset($_POST["contact_value"]) ? trim($_POST["contact_value"]) : "";
		$role = isset($_POST["role"]) ? strtolower(trim($_POST["role"])) : "donor";

		if($fullName == "" || $password == "" || $contactValue == "")
		{
			echo "Missing required fields";
			return;
		}
		if($role != "donor" && $role != "donee" && $role != "admin")
		{
			echo "Invalid role";
			return;
		}

		$userModel = new UserModel();
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);
		$userID = $userModel->createUser($fullName, $passwordHash);
		if(!$userID)
		{
			echo "Failed to create user";
			return;
		}
		$savedContact = $userModel->addContact($userID, $contactType, $contactValue, 1, 0);
		if(!$savedContact)
		{
			echo "User created, but failed to save contact";
			return;
		}
		$savedRole = $userModel->assignRole($userID, $role);
		if(!$savedRole)
		{
			echo "User created, but failed to assign role";
			return;
		}

		// Save simple role-specific EAV fields
		if($role == "donor")
		{
			$isAnonymous = isset($_POST["is_anonymous"]) ? $_POST["is_anonymous"] : "0";
			$isLaundering = isset($_POST["is_laundering_flag"]) ? $_POST["is_laundering_flag"] : "0";
			$userModel->saveAttributeValue($userID, "is_anonymous", $isAnonymous);
			$userModel->saveAttributeValue($userID, "is_laundering_flag", $isLaundering);
		}
		else if($role == "donee")
		{
			$nationalID = isset($_POST["national_id"]) ? trim($_POST["national_id"]) : "";
			$bankAccount = isset($_POST["bank_account"]) ? trim($_POST["bank_account"]) : "";

			if($nationalID != "")
			{
				$userModel->saveAttributeValue($userID, "national_id", $nationalID);
			}
			if($bankAccount != "")
			{
				$userModel->saveAttributeValue($userID, "bank_account", $bankAccount);
			}
		}

		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$_SESSION["UserID"] = $userID;
		$_SESSION["UserRole"] = $role;

		$capabilities = self::buildCapabilitiesByRoles([ ["role_name" => $role] ]);
		$_SESSION["CanDonate"] = $capabilities["canDonate"];
		$_SESSION["CanCreatePost"] = $capabilities["canCreatePost"];
		$_SESSION["CanApproveVerification"] = $capabilities["canApproveVerification"];

		echo "User registered successfully";
	}

	public static function login()
	{
		if($_SERVER["REQUEST_METHOD"] != "POST")
		{
			return;
		}

		$contactType = isset($_POST["contact_type"]) ? $_POST["contact_type"] : "email";
		$contactValue = isset($_POST["contact_value"]) ? trim($_POST["contact_value"]) : "";
		$password = isset($_POST["password"]) ? $_POST["password"] : "";

		if($contactValue == "" || $password == "")
		{
			echo "Missing login fields";
			return;
		}

		if($contactType == "email")
		{
			$strategy = new EmailPasswordAuth();
		}
		else if($contactType == "phone")
		{
			$strategy = new PhonePasswordAuth();
		}
		else
		{
			echo "Invalid contact type";
			return;
		}

		$userModel = new UserModel();
		$userID = $strategy->authenticate($contactValue, $password);
		if(!$userID)
		{
			echo "Invalid credentials";
			return;
		}

		$roles = $userModel->getRolesByUserID($userID);
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		$_SESSION["UserID"] = $userID;
		$_SESSION["UserRole"] = isset($roles[0]["role_name"]) ? $roles[0]["role_name"] : null;

		$capabilities = self::buildCapabilitiesByRoles($roles);
		$_SESSION["CanDonate"] = $capabilities["canDonate"];
		$_SESSION["CanCreatePost"] = $capabilities["canCreatePost"];
		$_SESSION["CanApproveVerification"] = $capabilities["canApproveVerification"];

		echo "Login successful";
	}
	public static function getMyProfile()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		if(!isset($_SESSION["UserID"]))
		{
			echo "User not logged in";
			return;
		}
		$userModel = new UserModel();
		$profile = $userModel->getUserWithRolesAndAttributes($_SESSION["UserID"]);
		if(!$profile)
		{
			echo "User not found";
			return;
		}
		header("Content-Type: application/json");
		echo json_encode($profile);
	}

	public static function updateMyAttribute()
	{
		if($_SERVER["REQUEST_METHOD"] != "POST")
		{
			return;
		}

		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if(!isset($_SESSION["UserID"]))
		{
			echo "User not logged in";
			return;
		}

		$attributeName = isset($_POST["attribute_name"]) ? trim($_POST["attribute_name"]) : "";
		$valueText = isset($_POST["value_text"]) ? trim($_POST["value_text"]) : "";

		if($attributeName == "")
		{
			echo "attribute_name is required";
			return;
		}

		$userModel = new UserModel();
		$ok = $userModel->saveAttributeValue($_SESSION["UserID"], $attributeName, $valueText);

		if($ok)
		{
			echo "Attribute saved";
		}
		else
		{
			echo "Failed to save attribute";
		}
	}

	public static function requestVerification()
	{
		if($_SERVER["REQUEST_METHOD"] != "POST")
		{
			return;
		}

		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if(!isset($_SESSION["UserID"]))
		{
			echo "User not logged in";
			return;
		}

		$method = isset($_POST["method"]) ? trim($_POST["method"]) : "document";
		$note = isset($_POST["note"]) ? trim($_POST["note"]) : null;

		$userModel = new UserModel();
		$ok = $userModel->createVerificationRequest($_SESSION["UserID"], $method, $note);

		if($ok)
		{
			echo "Verification request submitted";
		}
		else
		{
			echo "Failed to submit verification request";
		}
	}

	private static function buildCapabilitiesByRoles($roles)
	{
		$userRole = new BaseUserRole();

		foreach($roles as $role)
		{
			if(!isset($role["role_name"]))
			{
				continue;
			}

			$roleName = strtolower($role["role_name"]);
			if($roleName == "donor")
			{
				$userRole = new DonorRoleDecorator($userRole);
			}
			else if($roleName == "donee")
			{
				$userRole = new DoneeRoleDecorator($userRole);
			}
			else if($roleName == "admin")
			{
				$userRole = new AdminRoleDecorator($userRole);
			}
		}

		return [
			"canDonate" => $userRole->canDonate(),
			"canCreatePost" => $userRole->canCreatePost(),
			"canApproveVerification" => $userRole->canApproveVerification(),
		];
	}
}

?>