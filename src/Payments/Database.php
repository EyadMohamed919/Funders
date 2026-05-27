<?php

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            $host = 'mysql-1ccf547d-funders-2026.d.aivencloud.com';
            $dbname = 'funders';
            $user = 'avnadmin';
            $pass = 'AVNS__Yvv8LAluMM87YxMrRr';
            $port = '10320';

            try {
                self::$connection = new PDO(
                    "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
                    $user,
                    $pass,
                    [PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false]
                );
                self::$connection->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}