<?php

class DB{

    public static $_instance;
    public static $_connexion;

    /**
     * BD constructor.
     */
    public function __construct()
    {
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8';
        $usr = DB_USER;
        $pwd = DB_PASSWORD;

        self::$_connexion = new \Slim\PDO\Database($dsn, $usr, $pwd);
    }

    public static function getInstance()
    {
        if ( ! isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getConnexion() {
        return self::$_connexion;
    }

}

global $db;
$db = DB::getInstance();
$db = $db->getConnexion();