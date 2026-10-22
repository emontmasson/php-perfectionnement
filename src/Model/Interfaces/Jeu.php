<?php declare(strict_types=1);
namespace App\Model\Interfaces; 
use App\Model\Enum\Indice;

interface Jeu
{
    public function estTerminee(): bool;
    public function getNbTentatives(): int;
}