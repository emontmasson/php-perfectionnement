<?php

require  '../vendor/autoload.php';

use App\Auth\SessionManager;
use App\Controller\UserController;


$userController = new UserController(new SessionManager());

$userController->login('user.php', 'Formation1*');
