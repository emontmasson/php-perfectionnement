<?php

declare(strict_types=1);
namespace App\Config\Traits;

use App\Log\FileLogger;


trait traitementEnv
{
    private static function getConfig($type) {
        $env = parse_ini_file(dirname(__DIR__, 3) . '/.env');

        if ($env === false) {
            (new FileLogger())->error("Connexion $type impossible : Fichier .env non trouvé" );
            throw new \RuntimeException('Fichier .env introuvable ou illisible');
        }

        return $env;
    }

}