<?php

    //define('__ROOT__', dirname(dirname(__FILE__)).'/public_html/' );
    define('__ROOT__', dirname(dirname(__FILE__)).'/cedule/' );
    setlocale(LC_TIME, "fr_CA.utf8");

    define("DB_DATABASE", "cedulede_cedule");
    define("DB_HOST", "127.0.0.1");
    define("DB_USER", "cedulede_admin");
    define("DB_PASSWORD", "B0{O3tSZKJSZ");

    require_once __ROOT__.'vendor/autoload.php';
    require_once __ROOT__.'db/DB.php';
    require_once __ROOT__ . 'classes/Project.php';
    require_once __ROOT__.'classes/Task.php';
    require_once __ROOT__.'classes/Helper.php';