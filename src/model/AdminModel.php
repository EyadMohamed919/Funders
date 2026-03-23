<?php
require_once __DIR__ . "../../../config/db.php";

class AdminModel{
    
    function checkAdmin($userID) {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("SELECT * FROM user 
        JOIN admin on user.user_id = admin.user_id WHERE user.user_id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0)
        {

            return 1;
        }
        else
        {
            return 0;
        }
    }

    function getAllAdmin() {
        $conn = getDatabaseConnection();
        $res = $conn->query("SELECT * FROM user 
        JOIN admin on user.user_id = admin.user_id");

        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    function addAdmin($userID) {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("INSERT INTO admin(user_id) VALUES (?)");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        if($stmt->affected_rows > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function deleteAdmin($userID) {
        $conn = getDatabaseConnection();
        $stmt = $conn->prepare("DELETE FROM admin WHERE user_id = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();

        if($stmt->affected_rows > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}

// $admin = new AdminModel();
// echo $admin->deleteAdmin(2);
// // var_dump($admin->getAllAdmin());
?>