<?php 
declare(strict_types=1);
namespace App\Controller;

use App\Auth\SessionManager;
use App\Model\Enum\Indice;
use App\Model\Enum\ModeJeu;
use App\Model\Joueur;
use App\Model\Partie;
use App\Repository\PartieRepository;
use App\Http\Trait\TraitementJson;

final class GameController
{
    private Partie $partie;  
    private ModeJeu $modeJeu;
    private readonly PartieRepository $partieRepository;
    use traitementJson;
    
    public function __construct(private readonly SessionManager $session) {
        $this->partieRepository = new PartieRepository();
        
    }



    private function verifierModeValide(string $mode): void {
        $mode = (int) $mode;
        $mode = ModeJeu::tryFrom($mode);

        if($mode == null) {
            $this->sendErrorJson("veuillez préciser un mode de jeu valide", 400);
            exit;
        }

        $this->modeJeu = $mode;
        
    }

    private function verifierPartieCourante(): void {
        $partie = $this->session->getPartieCourante();

        if(!$partie) {
            $this->sendErrorJson( "Partie introuvable", 404);
            exit;
        }

        
    }

    private function verifierIndiceValide(string $indice): ?Indice {
        $indice =  Indice::tryFrom($indice);

        if($indice == null) {
            $this->sendErrorJson( "veuillez préciser un indice valide", 400);
            
        }
        return $indice;
    }

    private function verifierActionValidePartie(ModeJeu $modeJeu): void {
        if( $this->session->getPartieCourante()->getMode() != $modeJeu) {
            $this->sendErrorJson( "Vous ne pouvez effectuer cette action dans ce mode de jeu", 400);
            exit;
        }
    }

    private function finPartie() {
        $this->partieRepository->save( $this->session->getPartieCourante());
        $this->session->terminerPartie();
        
    }

    public function show(): void {
        echo "test pour les routes";
    }

    public function creer(string $mode): void
    {

        $this->verifierModeValide($mode);

        $partie = $this->session->getJoueur()->demarrerPartie($this->modeJeu);

        $this->session->setPartieCourante($partie);

        $this->setJsonData(['message' => "Partie créée en mode " . $partie->getMode()->name], 201);
    }


    public function donnerIndice():void {
        
        $data  = $this->getJsonData();
        $indice = trim((string) ($data['indice'] ?? ''));    

        $this->verifierPartieCourante();

        $indiceJeu = $this->verifierIndiceValide($indice);
        
        $this->verifierActionValidePartie(ModeJeu::PROGRAMME_DEVINE);
        
        if($indiceJeu === Indice::TROUVE) {
            $this->setJsonData(['message' => "j'ai gagné"]);
            $this->finPartie();
        }
        else {
             $this->session->getPartieCourante()->regenererBorne($indiceJeu);
        }

    }

    public function fairePropososer():void {
        
        $this->verifierPartieCourante();

        $this->verifierActionValidePartie(ModeJeu::PROGRAMME_DEVINE);

        $this->setJsonData(['proposition' =>  $this->session->getPartieCourante()->proposer()]);
   
    }

    public function proposer():void {

        
        $data  = $this->getJsonData();
        $proposition = (int) trim((string) ($data['proposition'] ?? ''));
        
        $this->verifierPartieCourante();
        
        $this->verifierActionValidePartie(ModeJeu::JOUEUR_DEVINE);

        $resultat =  $this->session->getPartieCourante()->jouer($proposition);
        if($resultat == Indice::TROUVE) {
            $this->setJsonData(['message' => "Vous avez gagné"]);
            $this->finPartie();
        }
        else {
            $this->setJsonData(['indice' => $this->session->getPartieCourante()->jouer($proposition)->value]);
           
        }
        

    }
}
