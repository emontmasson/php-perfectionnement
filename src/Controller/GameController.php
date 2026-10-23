<?php 
<?php 
declare(strict_types=1);
namespace App\Controller;

use App\Auth\SessionManager;
use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;
use App\Model\Partie;
use App\Repository\PartieRepository;

final class GameController
{
    private Joueur $joueur;
    private Partie $partie;  
    private ModeJeu $modeJeu;
    
    public function __construct(private readonly SessionManager $session, private readonly PartieRepository $partieRepository) {}

   

    public function creer(int $mode): void
    {
       
    }


    public function donnerIndice(string $indice):void {
        
       

    }

    public function fairePropososer():void {
       
    }

    public function proposer(int $proposition):void {
        

    }

    public function show(): void {
        echo "test pour les routes";
    }
}
