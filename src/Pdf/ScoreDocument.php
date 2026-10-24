<?php
declare(strict_types=1);

namespace App\Pdf;

use TCPDF;

final class ScoreDocument extends TCPDF
{
    /*public function Header(): void
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Jeu de la devinette', 0, 1, 'C');
        $this->Line(15, 20, 195, 20);
    }

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(
            0, 10,
            'Généré le ' . date('d/m/Y') . ' - Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(),
            0, 0, 'C'
        );
    }*/

     /*
    private Joueur  $joueur ;

    
    public function Header(): void
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'Scores de ' . $joueur, 0, 1, 'C');
    }
    */

    public function __construct(private readonly string $numero)
    {
        // P = portrait, mm = unité, A4, true = unicode, UTF-8, false = pas de cache disque
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', false);

        $this->SetCreator('Jeu de devinette');
        $this->SetTitle('Historique des parties ' . $numero);
        $this->SetMargins(15, 25, 15);        // gauche, haut, droite (le haut laisse la place à l'en-tête)
        $this->SetAutoPageBreak(true, 20);    // saut de page auto, 20 mm de marge basse (pied de page)
    }

    /** Appelée automatiquement par AddPage() : dessine le haut de chaque page. */
    public function Header(): void
    {
        $this->SetFont('dejavusans', 'B', 12);
        $this->Cell(0, 10, 'Jeu de devinette : scores', 0, 1, 'L');
        $this->Line(15, 20, 195, 20);         // trait sous l'en-tête
    }

    /** Appelée automatiquement à la fin de chaque page : dessine le bas. */
    public function Footer(): void
    {
        $this->SetY(-15);                     // 15 mm avant le bas de la page
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(90, 10, 'Document n° ' . $this->numero, 0, 0, 'L');
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages(), 0, 0, 'R');
    }
}

