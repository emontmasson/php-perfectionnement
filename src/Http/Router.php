<?php
declare(strict_types=1);

namespace App\Http;

use App\Auth\SessionManager;
use App\Http\Trait\TraitementJson;
use App\Log\FileLogger;

/**
 * Associe une requête (méthode + chemin) à une action de contrôleur.
 * Gère aussi l'authentification et les erreurs de routage (401, 404, 405, 500).
 *
 * Les routes sans paramètre (/game/guess) sont toujours testées AVANT
 * les routes avec paramètre (/game/{mode}) : l'ordre de déclaration
 * n'a donc pas d'importance.
 */
final class Router
{
    // Le trait est utilisable ici : Router est une classe.
    // On récupère donc setJsonData() et sendErrorJson() pour les erreurs.
    use TraitementJson;

    /** Routes sans accolades, testées en premier */
    private array $routesStatiques = [];

    /** Routes avec accolades ({id}, {mode}...), testées ensuite */
    private array $routesParametrees = [];

    public function __construct(
        private readonly SessionManager $session
    ) {}

    /**
     * Déclare une route.
     *
     * @param string $methode       Méthode HTTP (GET, POST...)
     * @param string $motif         Chemin, avec paramètres éventuels : /games/{id}
     * @param string $controleur    Nom complet de la classe du contrôleur
     * @param string $action        Méthode du contrôleur à appeler
     * @param bool   $authRequise   true si l'utilisateur doit être connecté
     */
    public function add(
        string $methode,
        string $motif,
        string $controleur,
        string $action,
        bool $authRequise = true,
    ): void {
        $route = [$methode, $motif, $controleur, $action, $authRequise];

        // Présence d'accolades : route paramétrée, sinon route statique
        if (str_contains($motif, '{')) {
            $this->routesParametrees[] = $route;
        } else {
            $this->routesStatiques[] = $route;
        }
    }

    /**
     * Analyse la requête courante et appelle le bon contrôleur.
     */
    public function processRequest(): void
    {
        try {
            $this->session->demarrer();

            $methode = $_SERVER['REQUEST_METHOD'];

            // Chemin seul (sans ?query), sans "/" final, "/" si vide
            $chemin = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') ?: '/';

            // Les routes statiques passent toujours avant les routes paramétrées
            $toutesLesRoutes = [...$this->routesStatiques, ...$this->routesParametrees];

            $cheminTrouve = false;

            foreach ($toutesLesRoutes as [$routeMethode, $motif, $controleur, $action, $authRequise]) {
                // Transforme /games/{id} en regex avec groupe nommé
                $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $motif) . '$#';

                if (!preg_match($regex, $chemin, $correspondances)) {
                    continue;
                }

                // Le chemin existe, mais peut-être pas avec cette méthode
                $cheminTrouve = true;
                if ($routeMethode !== $methode) {
                    continue;
                }

                // Garde d'authentification : simple test, pas de middleware
                if ($authRequise && !$this->session->estConnecte()) {
                    $this->sendErrorJson('Non authentifié', 401);
                    return;
                }

                // Ne garde que les groupes nommés (ex. 'id'), pas les index numériques
                $parametres = array_filter($correspondances, 'is_string', ARRAY_FILTER_USE_KEY);

                // Instancie le contrôleur et appelle l'action avec les paramètres d'URL
                (new $controleur($this->session))->$action(...$parametres);
                return;
            }

            // Aucune route n'a abouti
            $cheminTrouve
                ? $this->sendErrorJson('Méthode non autorisée', 405)
                : $this->sendErrorJson('Route introuvable', 404);

        } catch (\Throwable $e) {
            // Dernier filet de sécurité : on logue le détail, le client reçoit un message générique
            (new FileLogger())->error($e->getMessage());
            $this->sendErrorJson('Erreur interne', 500);
        }
    }
}