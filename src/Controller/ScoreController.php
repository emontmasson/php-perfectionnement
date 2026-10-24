<?php
declare(strict_types=1);

namespace App\Controller;

use App\Auth\SessionManager;
use App\Http\Trait\TraitementJson;
use App\Model\Joueur;
use App\Pdf\ScorePDF;
use App\Repository\PartieRepository;

final class ScoreController
{
    use TraitementJson;

    public function __construct(private readonly SessionManager $session) {}

    public function pdf(): void
    {
        $uid     = $this->session->getJoueur()->getUid();
        $joueur  = new Joueur($uid, $uid, '', '');
        $parties = (new PartieRepository())->findByJoueur($joueur);

        $fichier = (new ScorePDF())->generer($joueur, $parties);

        try {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $fichier['nom'] . '"');
            header('Content-Length: ' . filesize($fichier['chemin']));
            readfile($fichier['chemin']);
        } finally {
            unlink($fichier['chemin']);        // nettoyage même en cas d'erreur
        }
    }
}