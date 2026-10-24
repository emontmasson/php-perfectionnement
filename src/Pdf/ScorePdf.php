<?php
declare(strict_types=1);

namespace App\Pdf;

use App\Model\Joueur;
use App\Pdf\ScoresDocument;
use TCPDF;

final class ScorePdf 
{
    private const TEMPLATE = __DIR__ . '/../../templates/scores.php';

    /**
     * Génère le PDF des scores dans un fichier temporaire.
     *
     * @param Partie[] $parties
     * @return array{chemin: string, nom: string} chemin du fichier et nom de téléchargement
     */
    public function generer(Joueur $joueur, array $parties): array
    {
        $numero = $this->genererNumero();

        // 1. Template -> chaîne HTML
        $html = $this->rendreTemplate($joueur, $parties, $numero);

        // 2. Chaîne HTML -> document PDF
        $pdf = new ScoreDocument($numero);
        $pdf->AddPage();                       // déclenche Header()
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->writeHTML($html, true, false, true, false, '');

        // 3. Document -> fichier temporaire
        $chemin = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $numero . '.pdf';
        $pdf->Output($chemin, 'F');            // déclenche Footer() puis écrit le fichier

        return ['chemin' => $chemin, 'nom' => $numero . '.pdf'];
    }

    private function genererNumero(): string
    {
        // Date + 8 caractères hexadécimaux aléatoires : ex. SCORES-20261024-9f3a1c7e
        return 'SCORES-' . date('Ymd') . '-' . bin2hex(random_bytes(4));
    }

    private function rendreTemplate(Joueur $joueur, array $parties, string $numero): string
    {
        ob_start();
        require self::TEMPLATE;                // $joueur, $parties, $numero sont visibles dans le template
        return (string) ob_get_clean();
    }
}

