<?php
$host = 'mysql-1ccf547d-funders-2026.d.aivencloud.com';
$port = 10320;
$db   = 'funders';
$user = 'avnadmin';
$pass = 'AVNS__Yvv8LAluMM87YxMrRr';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
    echo "Connected to MySQL Database";
}

function getDatabaseConnection()
{
    global $conn;
    return $conn;
}

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