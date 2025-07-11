<?php

namespace App\Database;

use PDO;
use PDOException;

class DbManager
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/config.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";

            try
            {
                self::$pdo = new PDO($dsn, $config['user'], $config['password']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
            catch (PDOException $e)
            {
                die('Erreur de connexion DB: ' . $e->getMessage() . " / Vérfiez les paramètres de config.php");
            }
        }

        return self::$pdo;
    }
}
