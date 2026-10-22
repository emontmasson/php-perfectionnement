<?php 
declare(strict_types=1);
namespace App\Controller;

use App\Auth\SessionManager;
use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;
use App\Model\Partie;

final class GameController
{
    private Joueur $joueur;
    private Partie $partie;  
    private ModeJeu $modeJeu;
    
    public function __construct(private readonly SessionManager $session) {}

    private function verifierModeValide(int $mode): void {
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

    private function verifierJoueurConnecte() : void{
        $joueur = $this->session->getJoueur();

        if(!$joueur) {
            echo "Joueur introuvable";
            return;
        }

        $this->joueur = $joueur;
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

    public function creer(int $mode): void
    {
        $this->verifierModeValide($mode);
        
        $this->verifierJoueurConnecte();

        $partie = $this->joueur->demarrerPartie($this->modeJeu);

        $this->session->setPartieCourante($partie);

        echo "Partie créée en mode " . $partie->getMode()->name."<br>";
    }


    public function donnerIndice(string $indice) {
        
        $this->verifierJoueurConnecte();
        
        $this->verifierPartieCourante();

        $indiceJeu = $this->verifierIndiceValide($indice);
        
        $this->verifierActionValidePartie(ModeJeu::PROGRAMME_DEVINE);
        
        // on donne l'indice à la partie

        $this->partie->regenererBorne($indiceJeu);
    }

    public function fairePropososer() {
        $this->verifierJoueurConnecte();
        
        $this->verifierPartieCourante();

        $this->verifierActionValidePartie(ModeJeu::PROGRAMME_DEVINE);


        echo $this->partie->proposer()."<br>";
    }

    public function proposer(int $proposition) {
        

        $this->verifierJoueurConnecte();
        
        $this->verifierPartieCourante();
        
        $this->verifierActionValidePartie(ModeJeu::JOUEUR_DEVINE);

        echo $this->partie->jouer($proposition)->value."<br>"; 

    }
}
