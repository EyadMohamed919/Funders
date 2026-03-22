<?php
require_once __DIR__ . '/..\vendor\autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
    //Remove el comment law 3ayez to debug bas
    //echo "Connected to MySQL Database";
}

function getDatabaseConnection()
{
    global $conn;
    return $conn;
}

?>