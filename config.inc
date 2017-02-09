<?php
    //define('__ROOT__', dirname(dirname(__FILE__)).'/public_html/' );
    define('__ROOT__', dirname(dirname(__FILE__)).'/cedule/' );
    setlocale(LC_TIME, "fr_CA");

    define("DB_DATABASE", "cedulede_cedule");
    define("DB_HOST", "127.0.0.1");
    define("DB_USER", "cedulede_admin");
    define("DB_PASSWORD", "B0{O3tSZKJSZ");

    //require_once __ROOT__.'vendor/autoload.php';
    //require_once __ROOT__.'db/BD.php';

    require_once __ROOT__.'helpers/Helper.php';
    require_once __ROOT__.'helpers/TachesHelper.php';
    require_once __ROOT__.'controllers/ProjetController.php';
    require_once __ROOT__.'classes/Model.php';
    require_once __ROOT__.'classes/Projet.php';
    require_once __ROOT__.'classes/Tache.php';

    global $helper;

?>