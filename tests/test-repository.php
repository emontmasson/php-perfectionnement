<?php
require  '../vendor/autoload.php';

use App\Model\Enum\ModeJeu;
use App\Model\Enum\Borne;
use App\Model\Enum\Indice;
use App\Model\Joueur;
use App\Repository\PartieRepository;


$repo = new PartieRepository();

// ADAPTER : constructeur de Joueur (uid, nom...)
$joueurs = [
    new Joueur(uid: 'u-001', login: "dalice", nom: "Dupont", prenom:"Alice" ),
    new Joueur(uid: 'u-002', login: "bdylan", nom: "Bob" , prenom:"Dylan"),
    new Joueur(uid: 'u-003', login: "wwonka", nom: "Wonka", prenom: "Willy"),
];

echo "=== Simulation de parties ===\n\n";


foreach ($joueurs as $joueur) {
    $nbParties = random_int(2, 5);

    for ($i = 1; $i <= $nbParties; $i++) {
        $mode = random_int(0, 1) === 0
            ? ModeJeu::JOUEUR_DEVINE
            : ModeJeu::PROGRAMME_DEVINE;

        $partie = $joueur->demarrerPartie($mode);

        // INSERT : l'id est attribué ici
       
        $repo->save($partie);
       
        

        if ($mode === ModeJeu::JOUEUR_DEVINE) {
                $min = Borne::BORNE_MIN->value;
                $max = Borne::BORNE_MAX->value;

                while (!$partie->estTerminee()) {
                    $proposition = intdiv($min + $max, 2);

                    $partie->jouer($proposition);
                    $indice = $partie->donnerIndice($proposition);

                    if ($indice === Indice::PLUS_PETIT) {
                        $max = $proposition - 1;
                    } elseif ($indice === Indice::PLUS_GRAND) {
                        $min = $proposition + 1;
                    }
                }
        } else {
            $secretDuJoueur = random_int(Borne::BORNE_MIN->value, Borne::BORNE_MAX->value);

            while (!$partie->estTerminee()) {
                $proposition = $partie->proposer();

                if ($proposition > $secretDuJoueur) {
                    $indice = Indice::PLUS_PETIT;
                } elseif ($proposition < $secretDuJoueur) {
                    $indice = Indice::PLUS_GRAND;
                } else {
                    $indice = Indice::TROUVE;
                }

                $partie->setReponseJoueur($indice);
                $partie->jouer($proposition);
            }
        }

        // UPDATE : nb_tentatives et terminee
        $repo->save($partie);
        echo "<pre>";
        printf(
            "%-12s | partie #%-3d | %-18s | %2d tentative(s) | %s\n",
            $joueur->getUid(),
            $partie->getId(),
            $mode->value,
            $partie->getNbTentatives(),
            $partie->estTerminee() ? 'terminée' : 'en cours'
        );
    }
}

echo "\n=== Relecture depuis la base ===\n";
foreach ($joueurs as $joueur) {
    $parties = $repo->findByJoueur($joueur);
    printf("%-12s : %d partie(s) en base\n", $joueur->getUid(), count($parties));
}