<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Auth\SessionManager;
use App\Controller\{UserController, GameController, ScoreController};
use App\Http\Router;


$routeur = new Routeur(new SessionManager());

// Déclaration des routes : 
/**
     * $methode       Méthode HTTP (GET, POST...)
     * $motif         Chemin, avec paramètres éventuels : /games/{id}
     * $controleur    Nom complet de la classe du contrôleur
     * $action        Méthode du contrôleur à appeler
     * $authRequise   true si l'utilisateur doit être connecté
*/
// ----- Authentification -----

// Connexion via LDAP. Public : c'est la seule route accessible sans être connecté.
// Corps JSON : {"login": "...", "password": "..."}
// Réponses : 200 (ok), 400 (champs manquants), 401 (identifiants invalides)
$routeur->add('POST', '/login', UserController::class, 'login', authRequise: false);

// Déconnexion : détruit la session (y compris la partie en cours).
// Réponses : 200 (ok), 401 (non connecté)
$routeur->add('POST', '/logout', UserController::class, 'logout');

// ----- Partie en cours (stockée en session) -----

/* TODO : à compléter
        
*/

// pour tester :
$routeur->add('GET', '/game', GameController::class, 'show');

// TP ROUTE : /game/{mode} Démarre une nouvelle partie 
/* TP API :  Pas de corps.
   Réponses : 201 (créée), 400 (mode invalide), 401 (non connecté)
*/

// TP ROUTE : /game/guess MODE 1 UNIQUEMENT : le joueur propose un nombre, le programme répond par un indice : 
/* TP API :  Corps : {"proposition": 42}
// Réponses : 200 ({"indice": "plus_grand"}), 400 (valeur invalide ou hors bornes),
//            404 (aucune partie), 409 (partie d'un autre mode), 401
*/

// TP ROUTE : /game/proposal MODE 2 UNIQUEMENT : le programme propose un nombre (calculé depuis ses bornes).
/* TP API :  Pas de corps.
// Réponses : 200 ({"proposition": 50}), 404 (aucune partie), 409 (partie d'un autre mode), 401
*/

// TP ROUTE : /game/hint MODE 2 UNIQUEMENT : le joueur donne son indice sur la dernière proposition du programme.
/* TP API :  Corps : {"indice": "plus_grand"}  (plus_grand | plus_petit | trouve)
// Met à jour les bornes du programme. Si "trouve", la partie est terminée et enregistrée.
// Réponses : 200, 400 (indice invalide), 404, 409, 401
*/




// ----- Scores (lus en base) -----

// Liste les parties terminées du joueur connecté, au format JSON.
// Réponses : 200 (ok), 401
$routeur->add('GET', '/scores', ScoreController::class, 'list');

// Génère le PDF des scores (TP11), éventuellement envoyé par mail.
// Réponses : 200 (PDF), 401
$routeur->add('GET', '/scores/pdf', ScoreController::class, 'pdf');


$routeur->processRequest();