<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    public static function make($array)
    {
        try {
            return new PDO($array["connection"] . ";dbname=" . $array["dbname"], $array["dbuser"], $array["dbpassword"], $array["options"]);
        } catch (PDOException $e) {
            die();
        }
    }
}