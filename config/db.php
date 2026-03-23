<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = (int) ($_ENV['DB_PORT'] ?? 3306);
$db = $_ENV['DB_NAME'] ?? '';
$user = $_ENV['DB_USER'] ?? '';
$pass = $_ENV['DB_PASS'] ?? '';
$sslCa = $_ENV['DB_SSL_CA'] ?? '';
$sslCert = $_ENV['DB_SSL_CERT'] ?? '';
$sslKey = $_ENV['DB_SSL_KEY'] ?? '';

$conn = mysqli_init();

if ($conn === false) {
    die('Failed to initialize MySQL connection.');
}

$conn->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

$useSsl = $sslCa !== '' || $sslCert !== '' || $sslKey !== '';

if ($useSsl) {
    $conn->ssl_set(
        $sslKey !== '' ? $sslKey : null,
        $sslCert !== '' ? $sslCert : null,
        $sslCa !== '' ? $sslCa : null,
        null,
        null
    );
}

$flags = $useSsl ? MYSQLI_CLIENT_SSL : 0;
$connected = $conn->real_connect($host, $user, $pass, $db, $port, null, $flags);

if (!$connected) {
    die('Connection failed to ' . $host . ':' . $port . '/' . $db . ' - ' . mysqli_connect_error());
}

$conn->set_charset('utf8mb4');

function getDatabaseConnection()
{
    global $conn;
    return $conn;
}

?>