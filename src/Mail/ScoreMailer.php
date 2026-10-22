<?php

declare(strict_types=1);

namespace App\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ScoreMailer
{
    /**
     * Envoie par mail la liste des scores d'un joueur.
     *
     * @param string      $destinataire Adresse mail du joueur
     * @param string      $nomJoueur    Nom affiché dans le mail
     * @param array       $parties      Liste des parties (tableaux associatifs)
     * @param string|null $pdfContenu   Contenu binaire d'un PDF à joindre (optionnel)
     *
     * @throws Exception si l'envoi échoue
     */
    public function sendScores(        string $destinataire,
        string $nomJoueur,
        array $parties,
        ?string $pdfContenu = null
    ): void {

    }

}