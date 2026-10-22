<?php declare(strict_types=1);
namespace App\Model; // TODO: implémenter ce fichier pendant le TP.

use App\Model\Enum\Borne;
use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;
use App\Model\Partie;
use DateTimeImmutable;

final class PartieJoueurDevine extends Partie{

    private int $nombreSecret;

    public function __construct(Joueur $joueur, ?int $id = null, ?DateTimeImmutable $dateCreation = null) {

        parent::__construct($joueur, ModeJeu::JOUEUR_DEVINE, $id, );
        $this->nombreSecret = random_int(Borne::BORNE_MIN->value, Borne::BORNE_MAX->value);
       
    }

    public function donnerIndice(int $proposition): Indice
    {
        return match (true) {
            $proposition > $this->nombreSecret => Indice::PLUS_PETIT,
            $proposition < $this->nombreSecret => Indice::PLUS_GRAND,
            default => Indice::TROUVE,
        };
    }

    public function getNombreSecret() {
        return $this->nombreSecret;
    }


    public function jouer(int $proposition) : Indice {
        $this->nbTentatives++;
        $indice = $this->donnerIndice($proposition);
        $this->terminee = ($indice === Indice::TROUVE);
        return $indice;
    }
}