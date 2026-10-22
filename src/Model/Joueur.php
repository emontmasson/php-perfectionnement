<?php declare(strict_types=1);
namespace App\Model;
use App\Model\Enum\ModeJeu;

class Joueur
{
    public function __construct(
        private readonly string $uid,
        private readonly string $login,
        private readonly string $nom,
        private readonly string $prenom,
    ) {}

    public function getUid(): string { return $this->uid; }
    public function getLogin(): string { return $this->login; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }

    public function __toString(): string
    {
        return "{$this->prenom} {$this->nom} ({$this->login})";
    }

    public function demarrerPartie(ModeJeu $modeJeu): Partie {
        
        if($modeJeu == ModeJeu::JOUEUR_DEVINE) {
            
            return new PartieJoueurDevine($this);
        }
        else {
            return new PartieProgrammeDevine($this );
        }
    }
   

}
