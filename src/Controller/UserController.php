<?php 
declare(strict_types=1); 
namespace App\Controller;

use App\Auth\LdapAuthentificator;
use App\Auth\SessionManager;
use App\Http\Trait\TraitementJson;
use App\Model\Joueur;
use RuntimeException;

final class UserController {
    
    use TraitementJson;
 

    public function login(): void
    {
        $data  = $this->getJsonData();
        $login = trim((string) ($data['login'] ?? ''));
        $mdp   = (string) ($data['password'] ?? '');

        if ($login === '' || $mdp === '') {
            $this->sendErrorJson('Login et mot de passe requis');
            return;
        }

        $infos = (new LdapAuthentificator())->authentifier($login, $mdp);

        if ($infos === null) {
            $this->sendErrorJson('Identifiants invalides', 401);
            return;
        }

        $this->setJsonData(['message' => 'Connexion réussie']);
    }

    public function __construct(private readonly SessionManager $session) {}

   /* public function login(string $login, string $mdp): void
    {


        try {
            // suppression des espaces du login
            $login = trim($login);

            // vérifier que les champs soient bien remplis avant de les envoyer au modèle
            if ($login === '' || $mdp === '') {
                echo "Login et mot de passe requis";
                return;
            }

            // on essaie d'authentifier le joueur
            $infos = (new LdapAuthentificator())->authentifier($login, $mdp);

            // si la méthode ne renvoie rien alors les identifiants sont incorrectes
            if ($infos === null) {
                echo "Identifiants invalides";
                return;
            }

            // sinon instancie un joueur
            $joueur = new Joueur(uid: $infos['uid'], nom: $infos['nom'], login: $login, prenom: $infos['prenom']);

            // on met le joueur en session
            $this->session->connecter($joueur);

            echo "Connexion réussie : {$joueur}";
        }
        catch(RuntimeException $e) {
            echo $e->getMessage();
        }
        
    }*/

    public function logout(): void {
        $this->session->deconnecter();
    }

    
    
}







