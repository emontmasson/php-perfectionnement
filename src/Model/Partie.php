<?php declare(strict_types=1);
namespace App\Model; // TODO: implémenter ce fichier pendant le TP.

use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;
use App\Model\Interfaces\Jeu;
use DateTimeImmutable;   
abstract class Partie implements Jeu {
   
    // propriétés


  public function __construct(protected Joueur $joueur,protected ModeJeu $modeJeu, protected ?int $id = null, protected  ?DateTimeImmutable $dateCreation = null,protected int $nbTentatives = 0, protected bool $terminee = false) 
  {
    
  }

   // accesseurs 


   // méthodes
}