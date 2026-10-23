<?php declare(strict_types=1);
namespace App\Repository;

use App\Database\Connection;
use App\Model\Partie;
use App\Model\PartieJoueurDevine;
use App\Model\PartieProgrammeDevine;
use App\Model\Joueur;
use App\Model\Enum\ModeJeu;

use App\Repository\Traits\traitementSQL;



class PartieRepository 
{
    use traitementSQL;
    private \PDO $pdo;
    private string $tableName = 'parties'; 


    public function __construct()
    {
        
        $this->pdo = Connection::getInstance();
    }


    public function save(Partie $partie): void
    {
        
        $this->executer(function () use ($partie) {
            if ($partie->getId() === null) {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO {$this->tableName} (joueur_id, mode, nb_tentatives)
                    VALUES (:joueurId, :modeJeu, :nbTentatives)"
                );
                $stmt->execute([
                    'joueurId'     => $partie->getJoueur()->getUid(),
                    'modeJeu'      => $partie->getMode()->value,
                    'nbTentatives' => $partie->getNbTentatives()
                ]);
                $partie->setId((int) $this->pdo->lastInsertId());
            } else {
                $stmt = $this->pdo->prepare(
                    "UPDATE {$this->tableName}
                    SET nb_tentatives = :nbTentatives WHERE id = :id"
                );
                $stmt->execute([
                    'nbTentatives' => $partie->getNbTentatives(),
                    'id'           => $partie->getId(),
                ]);

                
            }

        }, 'save(Partie)');
    }
public function findByJoueur(Joueur $joueur): array
{
   return $this->executer(function () use ($joueur) {
         $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE joueur_id = :joueur_id");
        $stmt->execute(['joueur_id' => $joueur->getUid()]);

        $parties = [];
        foreach ($stmt->fetchAll() as $ligne) {

            $mode = ModeJeu::from($ligne['mode']);

            switch ($mode) {
                case ModeJeu::JOUEUR_DEVINE:
                    $classe = PartieJoueurDevine::class;
                    break;
                case ModeJeu::PROGRAMME_DEVINE:
                    $classe = PartieProgrammeDevine::class;
                    break;
            }

            $parties[] = new $classe(
                joueur: $joueur,
                id: (int) $ligne['id'],
            );
        }

        return $parties;

   }, 'findByJoueur($joueur)');
    
}

}
