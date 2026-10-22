<?php

declare(strict_types=1);

require  '../vendor/autoload.php';

use App\Model\Enum\Borne;
use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;

$joueur = new Joueur("u-02", "emontmasson", "montmasson", "elodie");
$partie = $joueur->demarrerPartie(ModeJeu::PROGRAMME_DEVINE);

// Le joueur (simulé) a pensé à ce nombre
$secretDuJoueur = random_int(Borne::BORNE_MIN->value, Borne::BORNE_MAX->value);
echo "Le joueur a pensé à : $secretDuJoueur<br/>";

while (!$partie->estTerminee()) {
    $proposition = $partie->proposer();
    switch ($proposition) {
        case $proposition > $secretDuJoueur:
            $indice = Indice::PLUS_PETIT;
            break;
        case $proposition < $secretDuJoueur:
            $indice = Indice::PLUS_GRAND;
             break;
        default:
            $indice = Indice::TROUVE;
             break;
    }
    echo $indice->value."<br/>";
    $partie->setReponseJoueur($indice);
    $partie->jouer($proposition);

    echo "Programme propose $proposition → joueur répond {$indice->value}<br/>";
}

echo "Trouvé en {$partie->getNbTentatives()} tentatives";







