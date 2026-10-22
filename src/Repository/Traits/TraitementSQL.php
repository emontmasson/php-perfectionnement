<?php
declare(strict_types=1);
namespace App\Repository\Traits;

use App\Log\FileLogger;
use PDOException;
use Throwable;

trait traitementSQL
{
    private function logErreurSql(PDOException $e, string $operation): void
    {
        $table = $this->tableName;                 
        

        switch ($e->getCode()) {
            case "HY093":
                (new FileLogger())->error(" Erreur : Paramètres incorrects dans la requête ($operation sur $table)");
                break;
            case "42S02":
                (new FileLogger())->error(" Erreur : La table $table est inconnue ($operation)");
                break;
            case "42S22":   
                (new FileLogger())->error(" Une colonne de la table $table est inconnue ($operation)");
                break;
            default: 
                (new FileLogger())->error(" Erreur SQL : ".$e->getCode()." : ".$e->getMessage());
                break;
        }
    }    

    private function executer(callable $action, string $operation): mixed
    {
        try {
            return $action();
        } catch (PDOException $e) {
            echo "Erreur de base de données, veuillez contacter l'administrateur.";
            $this->logErreurSql($e, $operation);
            exit;
        } catch (Throwable $e) {
            echo "Erreur dans le système, veuillez contacter l'administrateur.";
            (new FileLogger())->error("Erreur ($operation) : " . $e->getMessage());
            exit;
        }
    }
}

