<?php 

declare(strict_types=1); 
namespace App\Controller;

use App\Auth\LdapAuthentificator;
use App\Auth\SessionManager;

use App\Model\Joueur;

final class UserController {

    public function __construct(private readonly SessionManager $session) {}

    public function login(string $login, string $mdp): void
    {
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

    public function logout(): void {
        $this->session->deconnecter();
    }

    
    
}







