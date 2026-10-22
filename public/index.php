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
$routeur->add(methode: 'POST', motif:'/login', controleur:UserController::class,  action:'login', authRequise:false);

/* TODO : à compléter
        
*/

// Démarre une nouvelle partie et la place en session.
// Corps JSON : {"mode": 1} (1 = joueur devine, 2 = programme devine)
// Réponses : 201 (créée), 400 (mode invalide), 401 (non connecté)

// MODE 1 UNIQUEMENT : le joueur propose un nombre, le programme répond par un indice.
// Corps : {"proposition": 42}
// Réponses : 200 ({"indice": "plus_grand"}), 400 (valeur invalide ou hors bornes),
//            404 (aucune partie), 409 (partie d'un autre mode), 401

// MODE 2 UNIQUEMENT : le programme propose un nombre (calculé depuis ses bornes).
// Pas de corps.
// Réponses : 200 ({"proposition": 50}), 404 (aucune partie), 409 (partie d'un autre mode), 401

// MODE 2 UNIQUEMENT : le joueur donne son indice sur la dernière proposition du programme.
// Corps : {"indice": "plus_grand"}  (plus_grand | plus_petit | trouve)
// Met à jour les bornes du programme. Si "trouve", la partie est terminée et enregistrée.
// Réponses : 200, 400 (indice invalide), 404, 409, 401

// Liste les parties terminées du joueur connecté, au format JSON.
// Réponses : 200 (ok), 401

// Génère le PDF des scores (TP11), éventuellement envoyé par mail.
// Réponses : 200 (PDF), 401

$routeur->processRequest();