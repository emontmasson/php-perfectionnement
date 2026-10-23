<?php

require  '../vendor/autoload.php';

use App\Auth\SessionManager;
use App\Controller\GameController;
use App\Model\Enum\ModeJeu;
use App\Model\Enum\Borne;
use App\Model\Enum\Indice;
use App\Model\Joueur;
use App\Repository\PartieRepository;


/*
$userController = new UserController(new SessionManager());

$userController->login('user.php', 'Formation1*');*/

$joueur = new Joueur("u-02", "emontmasson", "montmasson", "elodie");

$sessionManager = new SessionManager();

$sessionManager->connecter($joueur);

$gameController = new GameController($sessionManager, new PartieRepository());

$gameController->creer(2);

$gameController->fairePropososer();

$gameController->donnerIndice("plus_grand");

$gameController->fairePropososer();

$gameController->donnerIndice("trouve");



