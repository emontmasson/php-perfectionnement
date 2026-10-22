<?php 
declare(strict_types=1);

namespace App\Auth;

use App\Config\Traits\traitementEnv;
use App\Log\FileLogger;
use LDAP\Connection;
use RuntimeException;


final class LdapAuthentificator
{
    private readonly string $serveur;  
    private readonly string $domaine;
    private readonly string $baseDn;
    private Connection $connectionLDAP; 
    use traitementEnv;

    public function __construct() {

        // récupération de la configuration dans le fichier .env
        
        $env = self::getConfig("LDAP");
        $this->serveur = $env["LDAP_SERVER"]; // adresse du serveur
        $this->domaine = $env["LDAP_DOMAIN"]; // nom du domaine pour l'upn
        $this->baseDn = $env["LDAP_SERVICE_DN"]; // nom du dn

        /* TODO 1 : vérifier que l'extension LDAP est chargée
                   sinon lever une RuntimeException "Problème de configuration LDAP : veuillez contacter l'administrateur"
                   et sauvegarder dans les logs le message "Extension LDAP non chargée, vérifiez php.ini"
        */
        if (!extension_loaded('ldap')) {
            (new FileLogger())->error("Extension LDAP non chargée, vérifiez php.ini");
            throw new RuntimeException("Problème de connexion LDAP : veuillez contacter l'administrateur");
        }
    }

    private function connecter(): void
    {
        // Doit être appelé AVANT ldap_connect pour être pris en compte
        ldap_set_option(null, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);    

        /* TODO 2 :  Se connecter au serveur LDAP
                    Si la connexion échoue, 
                        sauvegarder dans les logs le message "Problème de configuration LDAP, veuillez vérifier l'url."
                        sinon lever une RuntimeException "Problème de configuration LDAP : veuillez contacter l'administrateur"
        */

        $this->connectionLDAP = ldap_connect($this->serveur);
        if(!$this->connectionLDAP) {
            (new FileLogger())->error("Problème de configuration LDAP, veuillez vérifier l'url.");
            throw new RuntimeException("Problème de configuration LDAP : veuillez contacter l'administrateur");
        }

        ldap_set_option($this->connectionLDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connectionLDAP, LDAP_OPT_REFERRALS, 0);
    }

    
    public function authentifier(string $login, string $motDePasse): ?array
    {
        
        try {
            $this->connecter();
            if (!@ldap_bind($this->connectionLDAP, "$login@{$this->domaine}", $motDePasse)) {
                (new FileLogger())->error("Echec d'authentification LDAP: ".ldap_errno($this->connectionLDAP)." - " . ldap_error($this->connectionLDAP));
                // si l'erreur est lié à la connexion alors on le signale à l'utilisateur sinon on retourne null
                
                if(ldap_errno($this->connectionLDAP) === -1) {
                    throw new RuntimeException("Echec de connexion au serveur LDAP: veuillez contacter l'administrateur");
                }
                
                return null;
            }

            $infos = $this->lireAttributs($login);
            if ($infos === null) {
                (new FileLogger())->error("Utilisateur '$login' authentifié mais introuvable dans l'annuaire");
                return null;
            }

            (new FileLogger())->info("Authentification LDAP réussie pour '$login'");
            return $infos;
        } finally {
            ldap_unbind($this->connectionLDAP);
        }
    }

    

    /*public function authentifier(string $login, string $motDePasse): bool
    {
        /* TODO 3 : authentifier l'utilisateur 
             
            Ouvrir la connexion avec connecter()
            Echapper le login
            Le dn est le suivant : "$login@{domaine}"
            Tenter le bind
                en cas d'échec : logger l'erreur, retourner false
            Fermer la connexion
            Logger le succès (login uniquement) et retourner true
        */
        

    /*    $this->connecter();

        try {
            $login = ldap_escape($login, '', LDAP_ESCAPE_DN);
            if (!@ldap_bind($this->connectionLDAP, "$login@{$this->domaine}", $motDePasse)) {
                
                (new FileLogger())->error("Echec d'authentification LDAP: ".ldap_error($this->connectionLDAP));
                return false;
            }

           (new FileLogger())->info("Authentification LDAP réussie pour '$login'");
            return true;
        } finally {
            ldap_unbind($this->connectionLDAP);
        }
    }*/

    private function lireAttributs(string $login): ?array
    {
        $filtre    = '(sAMAccountName=' . ldap_escape($login, '', LDAP_ESCAPE_FILTER) . ')';
        $attributs = ['sn', 'givenname', 'samaccountname'];

        $recherche = ldap_search($this->connectionLDAP, $this->baseDn, $filtre, $attributs);
        if ($recherche === false) {
            return null;
        }

        $entrees = ldap_get_entries($this->connectionLDAP, $recherche);
        if ($entrees['count'] === 0) {
            return null;
        }

        $e = $entrees[0];
        return [
            'uid'    => $e['samaccountname'][0],
            'nom'    => $e['sn'][0]        ?? 'Inconnu',
            'prenom' => $e['givenname'][0] ?? 'Inconnu',
        ];
    }
}





