<?php
namespace App\Db;

class Database {
    private static ?\PDO $connection = null;

    public static function connect(): \PDO {
        if (self::$connection !== null) {
            return self::$connection;
        }

        // Attempt to load .env automatically when vlucas/phpdotenv is available
        if (file_exists(__DIR__ . '/../../.env') && class_exists('\Dotenv\Dotenv')) {
            try {
                \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../')->safeLoad();
            } catch (\Throwable $e) {
                // ignore dotenv load errors, we'll fallback to getenv()
            }
        }

        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        // default to existing 'funders' DB since you already have it
        $database = getenv('DB_NAME') ?: 'funders';
        $username = getenv('DB_USER') ?: 'root';
        // support both DB_PASSWORD and DB_PASS
        $password = getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: '';
        $socket = getenv('DB_SOCKET') ?: null;

        // If DB_HOST contains a full DSN (like mysql://user:pass@host:port/db), parse it
        if (preg_match('#^[a-z]+://#', $host)) {
            $parts = parse_url($host);
            if ($parts !== false) {
                if (!empty($parts['host'])) {
                    $host = $parts['host'];
                }
                if (!empty($parts['port'])) {
                    $port = $parts['port'];
                }
                if (!empty($parts['user']) && (getenv('DB_USER') === false || getenv('DB_USER') === '')) {
                    $username = $parts['user'];
                }
                if (!empty($parts['pass']) && (getenv('DB_PASSWORD') === false && getenv('DB_PASS') === false)) {
                    $password = $parts['pass'];
                }
                if (!empty($parts['path'])) {
                    $p = ltrim($parts['path'], '/');
                    if ($p !== '') {
                        $database = $p;
                    }
                }
                if (!empty($parts['query'])) {
                    parse_str($parts['query'], $q);
                    if (!empty($q['dbname']) && (getenv('DB_NAME') === false || getenv('DB_NAME') === '')) {
                        $database = $q['dbname'];
                    }
                }
            }
        }

        if ($socket) {
            $dsn = "mysql:unix_socket=$socket;dbname=$database;charset=utf8mb4";
        } else {
            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
        }
        try {
            self::$connection = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            return self::$connection;
        } catch (\PDOException $e) {
            $msg = $e->getMessage();
            if (strpos($msg, '2002') !== false) {
                $msg .= ' — please check that MySQL is running and DB_HOST/DB_PORT or DB_SOCKET are correct.';
            }
            throw new \Exception('Database connection failed: ' . $msg);
        }
    }

    public static function disconnect(): void {
        self::$connection = null;
    }

    public static function executeSqlFile(string $filePath): bool {
        if (!file_exists($filePath)) {
            throw new \Exception("SQL file not found: $filePath");
        }

        $sql = file_get_contents($filePath);
        $db = self::connect();

        $statements = array_filter(array_map('trim', explode(';', $sql)), fn($s) => $s !== '');
        foreach ($statements as $stmt) {
            $db->exec($stmt);
        }

        return true;
    }
}
