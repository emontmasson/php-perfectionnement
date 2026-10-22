<?php

require  '../vendor/autoload.php';
use App\Auth\LdapAuthentificator;

try {
    $auth = new LdapAuthentificator();

    var_dump($auth->authentifier('user.php', 'Formation1*'));   // bool(true)
    var_dump($auth->authentifier('user.php', 'mauvais'));       // bool(false)
      
}
catch (RuntimeException $e){
    echo $e->getMessage();
}