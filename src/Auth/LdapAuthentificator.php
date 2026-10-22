<?php 
declare(strict_types=1);

namespace App\Auth;

use App\Config\Traits\traitementEnv;
use App\Log\FileLogger;
use LDAP\Connection;


class LdapAuthentificator
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
    }

    private function connecter(): \LDAP\Connection
    {

        // Doit être appelé AVANT ldap_connect pour être pris en compte
        ldap_set_option(null, LDAP_OPT_X_TLS_REQUIRE_CERT, LDAP_OPT_X_TLS_NEVER);   
        
         /* TODO 2 :  Se connecter au serveur LDAP
                    Si la connexion échoue, 
                        sauvegarder dans les logs le message "Problème de configuration LDAP, veuillez vérifier l'url."
                        sinon lever une RuntimeException "Problème de configuration LDAP : veuillez contacter l'administrateur"
        */


        
        ldap_set_option($this->connectionLDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connectionLDAP, LDAP_OPT_REFERRALS, 0);
    }

    public function authentifier(string $login, string $motDePasse): bool
    {
        /* TODO 3 : authentifier l'utilisateur 
            Refuser les champs vides (retourner false) 
            Ouvrir la connexion avec connecter()
            Echapper le login
            Le dn est le suivant : "$login@{domaine}"
            Tenter le bind
                en cas d'échec : logger l'erreur, retourner false
            Fermer la connexion
            Logger le succès (login uniquement) et retourner true
        */

        /* TODO 5 : récupérer les informations de l'utilisateur
            Changer le retour de la méthode en array
            Juste après le bind,
            Appeler lireAttributs($login);
            Si la méthode renvoie null
            Mettre dans les logs "Utilisateur '$login' authentifié mais introuvable dans l'annuaire"
            Sinon 
            Renvoyer le tableau
        */
    }

    private function lireAttributs(string $login): ?array
    {
        /* TODO 4 : récupérer les informations de l'utilisateur
            initialiser un filtre selon sAMAccountName en passant le login utilisateur
            les attributs à récupérer sont : 'sn', 'givenname', 'samaccountname'
            lancer une recherche et si elle retourne false
            - renvoyer null
            lire le résultat et le récupérer dans un tableau
            si le tableau ne contient pas de données
            - renvoyer null
            sinon, renvoyer le tableau suivant 
            'uid'    => samaccountname,
            'nom'    => sn,
            'prenom' => givenname,
            si les entrées ne contiennent pas de données, mettre "Inconnu"
            
        */
    }
}


