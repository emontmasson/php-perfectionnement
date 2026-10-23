<?php
declare(strict_types=1);

namespace App\Pdf;

use TCPDF;

final class ScoresDocument extends TCPDF
{
    public function Header(): void
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
    }
}

