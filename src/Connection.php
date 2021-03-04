<?php

namespace App;

class  Connection {

    public static function getPdo(): \PDO
    {
        return $pdo = new \PDO("mysql:dbname=tutoblog;host=127.0.0.1", "root", "",[
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }
}