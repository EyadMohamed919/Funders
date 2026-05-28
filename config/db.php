<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$host = isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : getenv('DB_HOST');
$port = isset($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : getenv('DB_PORT');
$db   = isset($_ENV['DB_NAME']) ? $_ENV['DB_NAME'] : getenv('DB_NAME');
$user = isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : getenv('DB_USER');
$pass = isset($_ENV['DB_PASS']) ? $_ENV['DB_PASS'] : getenv('DB_PASS');

if (!$port) {
    $port = 3306;
}

if (!$host || !$db || !$user || $pass === false || $pass === null) {
    die("Missing DB env vars. Add .env file with DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS");
}

// Aiven MySQL usually requires SSL.
$conn = mysqli_init();
if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
    mysqli_options($conn, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);
}

$flags = defined('MYSQLI_CLIENT_SSL') ? MYSQLI_CLIENT_SSL : 0;
$connected = @mysqli_real_connect($conn, $host, $user, $pass, $db, (int)$port, null, $flags);

if (!$connected) {
    // Fallback without SSL for local DB setups.
    $conn = new mysqli($host, $user, $pass, $db, (int)$port);
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
{
    //Remove el comment law 3ayez to debug bas
    //echo "Connected to MySQL Database";
    // echo "Connected to MySQL Database";
}

function getDatabaseConnection()
{
    global $conn;
    return $conn;
}

?>