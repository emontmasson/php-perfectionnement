<?php declare(strict_types=1);
namespace App\Repository;

use App\Database\Connection;
use App\Model\Partie;

class PartieRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::getInstance();
    }


    public function save(Partie $partie): void
    {
        
    }

    public function findByJoueur(Joueur $joueur): array
    {
        
    }
}