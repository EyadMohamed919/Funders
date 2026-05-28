<?php
require_once __DIR__ . "/../../../config/db.php";
class UserModel{
    private $userID;
    private $fullName;
    private $passwordHash;
    private $createdAt;
    private $conn;

    public function __construct(){$this->conn = getDatabaseConnection();}
    public function getUserID(){ return $this->userID; }
    public function getFullName(){ return $this->fullName; }
    public function getPasswordHash(){ return $this->passwordHash; }
    public function getCreatedAt(){ return $this->createdAt; }
    
    public function getUserByID($userID)
    {
        $userID = (int) $userID;
        $sql = $this->conn->query("SELECT * FROM users WHERE user_id = $userID LIMIT 1");
        if($sql && $sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            $this->userID = $row["user_id"];
            $this->fullName = $row["full_name"];
            $this->passwordHash = $row["password_hash"];
            $this->createdAt = $row["created_at"];
            return $row;
        }
        return null;
    }
    public function getContactsByUserID($userID)
    {
        $userID = (int) $userID;
        $sql = $this->conn->query("SELECT * FROM user_contacts WHERE user_id = $userID");
        $contacts = [];
        if($sql)
        {
            while($row = $sql->fetch_assoc())
            {
                $contacts[] = $row;
            }
        }
        return $contacts;
    }

    public function getRolesByUserID($userID)
    {
        $userID = (int) $userID;
        $roles = [];

        $userRoles = $this->conn->query("SELECT role_id FROM user_roles WHERE user_id = $userID");
        if(!$userRoles)
        {
            return $roles;
        }
        while($row = $userRoles->fetch_assoc())
        {
            $roleID = (int) $row["role_id"];
            $roleSQL = $this->conn->query("SELECT role_id, role_name FROM roles WHERE role_id = $roleID LIMIT 1");
            if($roleSQL && $roleSQL->num_rows > 0)
            {
                $roles[] = $roleSQL->fetch_assoc();
            }
        }

        return $roles;
    }

    public function getAttributesByUserID($userID)
    {
        $userID = (int) $userID;
        $attributes = [];
        $valuesSQL = $this->conn->query("SELECT attribute_id, value_text FROM user_attribute_values WHERE user_id = $userID");
        if(!$valuesSQL)
        {
            return $attributes;
        }

        while($valueRow = $valuesSQL->fetch_assoc())
        {
            $attributeID = (int) $valueRow["attribute_id"];
            $defSQL = $this->conn->query("SELECT attribute_name, data_type, is_required, applies_to_role_id FROM user_attribute_definitions WHERE attribute_id = $attributeID LIMIT 1");

            if($defSQL && $defSQL->num_rows > 0)
            {
                $def = $defSQL->fetch_assoc();
                $attributes[] = [
                    "attribute_id" => $attributeID,
                    "attribute_name" => $def["attribute_name"],
                    "data_type" => $def["data_type"],
                    "is_required" => $def["is_required"],
                    "applies_to_role_id" => $def["applies_to_role_id"],
                    "value_text" => $valueRow["value_text"]
                ];
            }
        }

        return $attributes;
    }

    public function getUserWithRolesAndAttributes($userID)
    {
        $user = $this->getUserByID($userID);
        if(!$user)
        {
            return null;
        }
        $user["contacts"] = $this->getContactsByUserID($userID);
        $user["roles"] = $this->getRolesByUserID($userID);
        $user["attributes"] = $this->getAttributesByUserID($userID);

        return $user;
    }

    private function getRoleIDByName($roleName)
    {
        $roleName = $this->conn->real_escape_string($roleName);
        $sql = $this->conn->query("SELECT role_id FROM roles WHERE role_name = '$roleName' LIMIT 1");
        if($sql && $sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            return (int) $row["role_id"];
        }
        return null;
    }

    private function getAttributeIDByName($attributeName)
    {
        $attributeName = $this->conn->real_escape_string($attributeName);
        $sql = $this->conn->query("SELECT attribute_id FROM user_attribute_definitions WHERE attribute_name = '$attributeName' LIMIT 1");
        if($sql && $sql->num_rows > 0)
        {
            $row = $sql->fetch_assoc();
            return (int) $row["attribute_id"];
        }
        return null;
    }

    public function createUser($fullName, $passwordHash)
    {
        $fullName = $this->conn->real_escape_string($fullName);
        $passwordHash = $this->conn->real_escape_string($passwordHash);
        $sql = $this->conn->query("INSERT INTO users (full_name, password_hash) VALUES ('$fullName', '$passwordHash')");
        if($sql && $this->conn->affected_rows > 0)
        {
            return $this->conn->insert_id;
        }
        return null;
    }
    public function addContact($userID, $contactType, $contactValue, $isPrimary = 1, $isVerified = 0)
    {
        if($contactType != "email" && $contactType != "phone")
        {
            return false;
        }
        $contactType = $this->conn->real_escape_string($contactType);
        $contactValue = $this->conn->real_escape_string($contactValue);
        $userID = (int) $userID;
        $isPrimary = (int) $isPrimary;
        $isVerified = (int) $isVerified;
        if($isPrimary == 1)
        {
            $this->conn->query("UPDATE user_contacts SET is_primary = 0 WHERE user_id = $userID AND contact_type = '$contactType'");
        }
        $sql = $this->conn->query("INSERT INTO user_contacts (user_id, contact_type, contact_value, is_primary, is_verified) VALUES ($userID, '$contactType', '$contactValue', $isPrimary, $isVerified)");

        return $sql ? true : false;
    }
    public function assignRole($userID, $roleName)
    {
        $userID = (int) $userID;
        $roleID = $this->getRoleIDByName($roleName);
        if(!$roleID)
        {
            return false;
        }
        $check = $this->conn->query("SELECT user_id FROM user_roles WHERE user_id = $userID AND role_id = $roleID LIMIT 1");
        if($check && $check->num_rows > 0)
        {
            return true;
        }
        $sql = $this->conn->query("INSERT INTO user_roles (user_id, role_id) VALUES ($userID, $roleID)");
        return $sql ? true : false;
    }
    public function saveAttributeValue($userID, $attributeName, $valueText)
    {
        $userID = (int) $userID;
        $attributeName = $this->conn->real_escape_string($attributeName);
        $valueText = $this->conn->real_escape_string($valueText);
        $attributeID = $this->getAttributeIDByName($attributeName);
        if(!$attributeID)
        {
            return false;
        }
        $check = $this->conn->query("SELECT user_id FROM user_attribute_values WHERE user_id = $userID AND attribute_id = $attributeID LIMIT 1");
        if($check && $check->num_rows > 0)
        {
            $sql = $this->conn->query("UPDATE user_attribute_values SET value_text = '$valueText' WHERE user_id = $userID AND attribute_id = $attributeID");
            return $sql ? true : false;
        }

        $sql = $this->conn->query("INSERT INTO user_attribute_values (user_id, attribute_id, value_text) VALUES ($userID, $attributeID, '$valueText')");
        return $sql ? true : false;
    }

    public function createVerificationRequest($userID, $method = "document", $note = null)
    {
        $userID = (int) $userID;
        $method = $this->conn->real_escape_string($method);
        $status = "pending";

        if($note === null)
        {
            $sql = $this->conn->query("INSERT INTO user_verification_requests (user_id, status, method) VALUES ($userID, '$status', '$method')");
        }
        else
        {
            $note = $this->conn->real_escape_string($note);
            $sql = $this->conn->query("INSERT INTO user_verification_requests (user_id, status, method, note) VALUES ($userID, '$status', '$method', '$note')");
        }

        return $sql ? true : false;
    }

    
}