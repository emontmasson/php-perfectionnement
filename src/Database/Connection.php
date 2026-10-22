<?php
declare(strict_types=1);

namespace App\Database;

use App\Config\Traits\traitementEnv;
use App\Log\FileLogger;
use PDO;
use PDOException;
use RuntimeException;

final class Connection
{
    private static ?PDO $instance = null;

    use traitementEnv;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
                $env = self::getConfig("SQL");

                $host = $env['DB_HOST'] ?? 'localhost';
              
                $dbname = $env['DB_NAME'] ?? null;
                $user = $env['DB_USER'] ?? null;
                $password =  $env['DB_PASSWORD'] ?? "";
                

                try {
                    self::$instance = new PDO(
                        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                        $user,
                        $password,
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );
                    (new FileLogger())->info(" $user connecté à la base $dbname");
                } catch (PDOException $e) {
                   (new FileLogger())->error(" Erreur : " . $e->getMessage());
                    throw new RuntimeException("Connexion à la base impossible", 0, $e);
                }

           
        }
        
        return self::$instance;
        
    }
}
