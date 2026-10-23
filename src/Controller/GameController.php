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
    private Partie $partie;  
    private ModeJeu $modeJeu;
    private readonly PartieRepository $partieRepository;
    
    public function __construct(private readonly SessionManager $session) {
        $this->partieRepository = new PartieRepository();
        
    }



    private function verifierModeValide(string $mode): void {
        $mode = (int) $mode;
        $mode = ModeJeu::tryFrom($mode);

        if($mode == null) {
            echo "veuillez préciser un mode de jeu valide";
            return;
        }

        $this->modeJeu = $mode;
        
    }

    private function verifierPartieCourante(): void {
        $partie = $this->session->getPartieCourante();

        if(!$partie) {
            echo "Partie introuvable";
            return;
        }

        $this->partie = $partie;
    }

    private function verifierIndiceValide(string $indice): ?Indice {
        $indice =  Indice::tryFrom($indice);

        if($indice == null) {
            echo "veuillez préciser un indice valide";
            
        }
        

        return $indice;
    }

    private function verifierActionValidePartie(ModeJeu $modeJeu): void {
        if($this->partie->getMode() != $modeJeu) {
            echo "Vous ne pouvez effectuer cette action dans ce mode de jeu";
            return;
        }
    }

    private function finPartie() {
        $this->session->terminerPartie();
        $this->partieRepository->save($this->partie);
    }

    public function show(): void {
        echo "test pour les routes";
    }

    public function creer(string $mode): void
    {

        $this->verifierModeValide($mode);

        $partie = $this->session?->getJoueur()?->demarrerPartie($this->modeJeu);

        $this->session->setPartieCourante($partie);

        echo "Partie créée en mode " . $partie->getMode()->name."<br>";
    }


    public function donnerIndice(string $indice):void {
        
        
        $this->verifierPartieCourante();

        $indiceJeu = $this->verifierIndiceValide($indice);
        
        $this->verifierActionValidePartie(ModeJeu::PROGRAMME_DEVINE);
        
        if($indiceJeu === Indice::TROUVE) {
            echo "j'ai gagné";
            $this->finPartie();
        }
        else {
            $this->partie->regenererBorne($indiceJeu);
        }

    }

    public function fairePropososer():void {
        
        $this->verifierPartieCourante();

        $this->verifierActionValidePartie(ModeJeu::PROGRAMME_DEVINE);


        echo $this->partie->proposer()."<br>";
    }

    public function proposer(string $proposition):void {
        
        
        $this->verifierPartieCourante();
        
        $this->verifierActionValidePartie(ModeJeu::JOUEUR_DEVINE);

        $resultat = $this->partie->jouer($proposition);
        if($resultat == Indice::TROUVE) {
            echo "Vous avez gagné";
            $this->finPartie();
        }
        else {
            echo $this->partie->jouer($proposition)->value."<br>"; 
        }
        

    }
}
