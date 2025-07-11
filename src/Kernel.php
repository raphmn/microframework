<?php

namespace App;
use App\Database\DbManager;
use App\Routeur;
use PDOException;

class Kernel
{
    public static function run()
    {
        try {
            define("PDO", DbManager::get());
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
        }
        Routeur::route();
    }
}