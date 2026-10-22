<?php

declare(strict_types=1);

require  '../vendor/autoload.php';


use App\Model\Enum\ModeJeu;
use App\Model\Enum\Borne;
use App\Model\Enum\Indice;
use App\Model\Joueur;


$joueur = new Joueur("u-02", "emontmasson", "montmasson", "elodie");
$partie = $joueur->demarrerPartie(ModeJeu::JOUEUR_DEVINE);
$min = Borne::BORNE_MIN->value;
$max = Borne::BORNE_MAX->value;
$proposition = intdiv($min + $max, 2);

do {
   
    $partie->jouer($proposition);
    $indice = $partie->donnerIndice($proposition)->value;
    echo "{$partie->getNombreSecret()}<br/>";
    echo "proposition : $proposition → $indice<br/>";

    if ($indice === Indice::PLUS_PETIT->value) {
        $max = $proposition - 1;           // le secret est plus bas
    } elseif ($indice === Indice::PLUS_GRAND->value) {
        $min = $proposition + 1;           // le secret est plus haut
    }

    $proposition = intdiv($min + $max, 2);

} while (!$partie->estTerminee());

echo "Nombre de tentatives ".$partie->getNbTentatives();








