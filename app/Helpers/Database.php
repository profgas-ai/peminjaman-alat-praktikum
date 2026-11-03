<?php
namespace App\Helpers;

use PDO;
use PDOException;

class Database 
{
    public static function connect() 
    {
        try {
            $dsn = "mysql:host=" . env('DB_HOST') . 
                   ";dbname=" . env('DB_DATABASE') . 
                   ";charset=utf8mb4";
            
            $pdo = new PDO(
                $dsn,
                env('DB_USERNAME'),
                env('DB_PASSWORD'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
            
            return $pdo;
            
        } catch (PDOException $e) {
            report($e);
            abort(500, 'Koneksi database gagal');
        }
    }
}