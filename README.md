# Guess Game : projet de formation

API PHP 8 du jeu du « plus ou moins ».

## Prérequis
- WAMP (Apache, MySQL, PHP 8.2+)
- Composer
- Extensions PHP : `ldap`, `pdo_mysql`, `mbstring`

## Structure final
guess-game/
├── .env.example              [FOURNI]
├── .gitignore                [FOURNI]
├── composer.json             [À CRÉER : TP1]
├── public/
│   ├── index.php             [FOURNI : front controller, complété au TP9]
│   └── .htaccess             [FOURNI]
├── src/
│   ├── Enum/Model/           [TP2]
│   │   ├── ModeJeu.php
│   │   └── Indice.php
│   ├── Model/
│   │   ├── Interfaces/
│   │   │   └── Jeu.php       [TP4]
│   │   ├── Traits/
│   │   │   └── Journalisable.php  [FOURNI]
│   │   ├── Joueur.php        [TP3]
│   │   ├── Partie.php        [TP5]
│   │   ├── PartieJoueurDevine.php   [TP5]
│   │   └── PartieProgrammeDevine.php [TP5]
│   ├── Database/
│   │   ├── Connection.php    [FOURNI]
│   │   └── init.php          [FOURNI : script d'init BD]
│   ├── Repository/
│   │   └── PartiesRepository.php  [TP6, avec save() fourni]
│   ├── Auth/
│   │   ├── LdapAuthentificator.php [TP8]
│   │   └── SessionManager.php      [TP8]
│   ├── Http/
│   │   ├── Router.php        [FOURNI]
│   │   ├── JsonResponse.php  [FOURNI]
│   │   └── AuthMiddleware.php [TP9]
│   ├── Controller/
│   │   ├── UserController.php  [TP9]
│   │   ├── GameController.php  [TP9]
│   │   └── ScoreController.php [TP10/TP11]
│   └── Pdf/
│       └── ScoresPdf.php     [TP11]
├── tests/
│   ├── test-model.php        [TP3 / TP5 : stagiaire]
│   └── test-repository.php   [FOURNI]
├── logs/                     [dossier vide fourni]
└── README.md                 [FOURNI : prise en main]