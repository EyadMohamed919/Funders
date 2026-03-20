<?php
require_once "../db.php";

class Model{
    function getAll(){
        $conn = getDatabaseConnection();
        $sql = "SELECT * FROM subscriptions";
        $result = $conn->query($sql);
        $data = [];
        while($row = $result->fetch_assoc())
        {
            $data[] = $row;
        }
        return $data;
    }
}


?>