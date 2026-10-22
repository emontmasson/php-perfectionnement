<?php 
declare(strict_types=1); 
namespace App\Auth;

use App\Model\Partie;

final class SessionManager
{
    private const CLE_UID = 'user';
    private const CLE_PARTIE = 'partie';

    public function demarrer(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,      // inaccessible au JavaScript (anti-XSS), qui exige une connexion HTTPS
                'samesite' => 'Lax',     // Bloque l'envoi des cookies depuis les sites tiers pour contrer le CSRF
                'secure'   => false,     // true en HTTPS
            ]);
            session_start();
        }
    }

    // --- Authentification ---

    public function connecter(string $uid): void
    {
        $this->demarrer();
        session_regenerate_id(true);     // anti fixation de session
        $_SESSION[self::CLE_UID] = $uid;
    }

    public function estConnecte(): bool
    {
        $this->demarrer();
        return isset($_SESSION[self::CLE_UID]);
    }

    public function getId(): ?string
    {
        $this->demarrer();
        return $_SESSION[self::CLE_UID] ?? null;
    }

    public function deconnecter(): void
    {
        $this->demarrer();
        $_SESSION = [];
        session_destroy();
    }

    // --- Partie en cours ---

    public function setPartieCourante(Partie $partie): void
    {
        $this->demarrer();
        $_SESSION[self::CLE_PARTIE] = $partie;
    }

    public function getPartieCourante(): ?Partie 
    {
        $this->demarrer();
        return $_SESSION[self::CLE_PARTIE] ?? null;
    }

    public function terminerPartie(): void
    {
        $this->demarrer();
        unset($_SESSION[self::CLE_PARTIE]);
    }
}
