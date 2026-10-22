<?php declare(strict_types=1);
namespace App\Model; // TODO: implémenter ce fichier pendant le TP.
use App\Model\Enum\Indice;
use App\Model\Enum\Borne;
use App\Model\Enum\ModeJeu;
use DateTimeImmutable;

final class PartieProgrammeDevine extends Partie{

    private int $min = Borne::BORNE_MIN->value;
    private int $max = Borne::BORNE_MAX->value;
    private int $proposition;

    public function __construct(Joueur $joueur, ?int $id = null,?DateTimeImmutable $dateCreation = null) {
        parent::__construct($joueur, ModeJeu::PROGRAMME_DEVINE, $id, $dateCreation);
    }

    public function proposer(): int
    {
        $this->proposition = intdiv($this->min + $this->max, 2);
        $this->nbTentatives++;
        return $this->proposition;
    }

    public function regenererBorne(Indice $indice): void
    {

        if ($this->proposition === null) {
            throw new \LogicException("Aucune proposition en cours");
        }
      
        match ($indice) {
            Indice::PLUS_PETIT => $this->max = $this->proposition - 1,
            Indice::PLUS_GRAND => $this->min = $this->proposition + 1,
            Indice::TROUVE     => $this->terminerPartie()
        };

    }

    
}