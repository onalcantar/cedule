<?php

    //Fichier de configuration d'accès à la BD pour les requêtes AJAX

    define("DB_DATABASE", "cedulede_cedule");
    define("DB_HOST", "127.0.0.1");
    define("DB_USER", "cedulede_admin");
    define("DB_PASSWORD", "B0{O3tSZKJSZ");

    include_once '../vendor/autoload.php';
    include_once 'DB.php';
    include_once '../classes/Helper.php';