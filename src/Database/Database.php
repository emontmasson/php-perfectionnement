<?php

declare(strict_types=1);

namespace App\Database;

use PDO;

final class Database
{
    public static function init(?PDO $pdo = null): void
    {
        $pdo = $pdo ?? Connection::getInstance();

        $pdo->exec("CREATE TABLE IF NOT EXISTS parties (
            id INT AUTO_INCREMENT PRIMARY KEY,
            joueur_id VARCHAR(255) NOT NULL,
            mode INT NOT NULL,
            nb_tentatives INT NOT NULL,
            terminee TINYINT(1) NOT NULL DEFAULT 0,
            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }
}