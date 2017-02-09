<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-08
 * Time: 21:33
 */

namespace Ubeo;

include_once 'vendor/autoload.php';

use Slim\PDO\Database;
class Helper
{
    protected static $db_connection;

    /**
     * Model constructor.
     * Instatiate a connection objet
     */
    public function __construct()
    {
        $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8';
        $usr = DB_USER;
        $pwd = DB_PASSWORD;
        self::$db_connection = new Database($dsn, $usr, $pwd);
    }
}