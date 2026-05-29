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
			header("Location: /RegisterPage.php?status=error&msg=" . urlencode("Missing required fields"));
			exit;
			return;
		}
		if($role != "donor" && $role != "donee")
		{
			header("Location: /RegisterPage.php?status=error&msg=" . urlencode("Admins are created manually"));
			exit;
			return;
		}

		$userModel = new UserModel();
		$passwordHash = password_hash($password, PASSWORD_DEFAULT);
		$userID = $userModel->createUser($fullName, $passwordHash);
		if(!$userID)
		{
			header("Location: /RegisterPage.php?status=error&msg=" . urlencode("Failed to create user"));
			exit;
			return;
		}
		$savedContact = $userModel->addContact($userID, $contactType, $contactValue, 1, 0);
		if(!$savedContact)
		{
			header("Location: /RegisterPage.php?status=error&msg=" . urlencode("User created, but failed to save contact"));
			exit;
			return;
		}
		$savedRole = $userModel->assignRole($userID, $role);
		if(!$savedRole)
		{
			header("Location: /RegisterPage.php?status=error&msg=" . urlencode("User created, but failed to assign role"));
			exit;
			return;
		}

		// Save simple role-specific EAV fields
		if($role == "donor")
		{
			$isAnonymous = isset($_POST["is_anonymous"]) ? $_POST["is_anonymous"] : "0";
			$userModel->saveAttributeValue($userID, "is_anonymous", $isAnonymous);
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

		header("Location: /ProfilePage.php?status=success&msg=" . urlencode("User registered successfully"));
		exit;
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
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Missing login fields"));
			exit;
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
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Invalid contact type"));
			exit;
			return;
		}

		$userModel = new UserModel();
		$userID = $strategy->authenticate($contactValue, $password);
		if(!$userID)
		{
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Invalid credentials"));
			exit;
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

		header("Location: /ProfilePage.php?status=success&msg=" . urlencode("Login successful"));
		exit;
	}
	public static function getMyProfile()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		if(!isset($_SESSION["UserID"]))
		{
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Please login first"));
			exit;
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
			header("Location: /ProfilePage.php?status=error&msg=" . urlencode("attribute_name is required"));
			exit;
			return;
		}

		$userModel = new UserModel();
		$ok = $userModel->saveAttributeValue($_SESSION["UserID"], $attributeName, $valueText);

		if($ok)
		{
			header("Location: /ProfilePage.php?status=success&msg=" . urlencode("Attribute saved"));
			exit;
		}
		else
		{
			header("Location: /ProfilePage.php?status=error&msg=" . urlencode("Failed to save attribute. Use: national_id, bank_account, is_anonymous"));
			exit;
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
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Please login first"));
			exit;
			return;
		}

		if(!isset($_SESSION["CanCreatePost"]) || !$_SESSION["CanCreatePost"])
		{
			header("Location: /ProfilePage.php?status=error&msg=" . urlencode("Only donee accounts can request verification"));
			exit;
			return;
		}

		$method = isset($_POST["method"]) ? trim($_POST["method"]) : "document";
		$note = isset($_POST["note"]) ? trim($_POST["note"]) : null;

		$userModel = new UserModel();
		$ok = $userModel->createVerificationRequest($_SESSION["UserID"], $method, $note);

		if($ok)
		{
			header("Location: /ProfilePage.php?status=success&msg=" . urlencode("Verification request submitted"));
			exit;
		}
		else
		{
			header("Location: /ProfilePage.php?status=error&msg=" . urlencode("Failed to submit verification request"));
			exit;
		}
	}

	public static function getAllVerificationRequests()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		if(!isset($_SESSION["UserID"]))
		{
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Please login first"));
			exit;
			return;
		}

		if(!isset($_SESSION["CanApproveVerification"]) || !$_SESSION["CanApproveVerification"])
		{
			header("Location: /ProfilePage.php?status=error&msg=" . urlencode("Permission denied"));
			exit;
			return;
		}

		$userModel = new UserModel();
		$requests = $userModel->getAllVerificationRequests();
		header("Content-Type: application/json");
		echo json_encode($requests);
	}

	public static function reviewVerificationRequest()
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
			header("Location: /LoginPage.php?status=error&msg=" . urlencode("Please login first"));
			exit;
			return;
		}

		if(!isset($_SESSION["CanApproveVerification"]) || !$_SESSION["CanApproveVerification"])
		{
			header("Location: /ProfilePage.php?status=error&msg=" . urlencode("Permission denied"));
			exit;
			return;
		}

		$verificationID = isset($_POST["verification_id"]) ? (int) $_POST["verification_id"] : 0;
		$status = isset($_POST["status"]) ? trim($_POST["status"]) : "";
		$note = isset($_POST["note"]) ? trim($_POST["note"]) : null;

		if($verificationID <= 0 || ($status != "approved" && $status != "rejected"))
		{
			header("Location: /AdminVerificationPage.php?status=error&msg=" . urlencode("Invalid review data"));
			exit;
			return;
		}

		$userModel = new UserModel();
		$ok = $userModel->reviewVerificationRequest($verificationID, $status, $_SESSION["UserID"], $note);

		if($ok)
		{
			header("Location: /AdminVerificationPage.php?status=success&msg=" . urlencode("Verification reviewed"));
			exit;
		}
		else
		{
			header("Location: /AdminVerificationPage.php?status=error&msg=" . urlencode("Failed to review verification"));
			exit;
		}
	}

	public static function logout()
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		$_SESSION = [];
		session_destroy();
		header("Location: /LoginPage.php");
		exit;
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