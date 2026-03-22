<?php
require_once "../db.php";

class Model{
    function getAll(){
        $conn = getDatabaseConnection();
        $sql = "SELECT * FROM funders";
        $result = $conn->query($sql);
        $data = [];
        while($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }
        return $data;
    }
    function checkAdmin($username, $password) {
        $conn = getDatabaseConnection();
        $res = $conn->query("SELECT * FROM adminlogintable WHERE Username='$username'");

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['Password'] === $password; // replace with hash later
        }
        return false;
    }

    function getAdmins() {
        $conn = getDatabaseConnection();
        $res = $conn->query("SELECT * FROM adminlogintable");

        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    function addAdmin($username, $password) {
        $conn = getDatabaseConnection();
        $conn->query("INSERT INTO adminlogintable (Username, Password)
                      VALUES ('$username', '$password')");
    }

    function deleteAdmin($username) {
        $conn = getDatabaseConnection();
        $conn->query("DELETE FROM adminlogintable WHERE Username='$username'");
    }
}


?>