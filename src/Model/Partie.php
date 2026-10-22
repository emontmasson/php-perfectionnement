<?php declare(strict_types=1);
namespace App\Model; 
use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;
use App\Model\Interfaces\Jeu;
use DateTimeImmutable;   
abstract class Partie implements Jeu {
   


    public function __construct(protected Joueur $joueur,protected ModeJeu $modeJeu, protected ?int $id = null, protected  ?DateTimeImmutable $dateCreation = null,protected int $nbTentatives = 0, protected bool $terminee = false) 
    {
        
        $this->dateCreation = $dateCreation ?? new DateTimeImmutable(); // soit la date est enregistrée en base de données soit on la créé
        
    }

    public function getNbTentatives() : int{
        return $this->nbTentatives;
    }

    public function getDateCreation() : string {
        return $this->dateCreation->format('d-m-Y H:i:sP');
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function estTerminee() : bool {
        return $this->terminee;
    }

    public function getMode(): ModeJeu {
        return $this->modeJeu;
    }

    public function getJoueur(): Joueur {
        return $this->joueur;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function terminerPartie() {
        $this->terminee = true;
    }

}