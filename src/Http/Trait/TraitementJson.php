<?php
declare(strict_types=1);

namespace App\Http\Trait;

/**
 * Trait regroupant la gestion du JSON pour les contrôleurs :
 * lecture du corps de la requête, envoi d'une réponse, envoi d'une erreur.
 */
trait TraitementJson
{
    /**
     * Lit le corps de la requête HTTP et le décode depuis le JSON.
     *
     * @return array<string, mixed> Données reçues, ou tableau vide si corps absent/invalide
     */
    protected function getJsonData(): array
    {
        // php://input : flux brut du corps de la requête (contrairement à $_POST,
        // qui ne contient que les formulaires classiques, pas le JSON)
        $brut = file_get_contents('php://input');

        // Corps absent (false) ou vide : inutile de décoder
        // Sinon json_decode(..., true) renvoie un tableau associatif
        // (sans le true, on obtiendrait un objet stdClass)
        $data = ($brut === false || $brut === '') ? [] : json_decode($brut, true);

        // json_decode renvoie null si le JSON est mal formé, et peut renvoyer
        // un scalaire (ex. "12" donne int). On ne garde que les tableaux.
        return is_array($data) ? $data : [];
    }

    /**
     * Envoie une réponse JSON au client.
     *
     * @param array<string, mixed> $data   Données à encoder
     * @param int                  $status Code HTTP (200 par défaut)
     */
    protected function setJsonData(array $data, int $status = 200): void
    {
        // Code de statut HTTP (200, 201, 400, 401, 404...)
        http_response_code($status);

        // Indique au client que le contenu est du JSON en UTF-8
        header('Content-Type: application/json; charset=utf-8');

        // JSON_UNESCAPED_UNICODE : garde les accents lisibles ("é" au lieu de "\u00e9")
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Envoie une réponse d'erreur au format uniforme {"erreur": "..."}.
     *
     * @param string $message Message d'erreur destiné au client
     * @param int    $status  Code HTTP d'erreur (400 par défaut)
     */
    protected function sendErrorJson(string $message, int $status = 400): void
    {
        // Délègue à setJsonData pour ne pas dupliquer la logique d'envoi
        $this->setJsonData(['erreur' => $message], $status);
    }
}