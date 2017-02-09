<?php
/**
 * Created by PhpStorm.
 * User: noealcantar
 * Date: 17-02-08
 * Time: 16:06
 */

namespace Ubeo;

include_once 'vendor/autoload.php';

use Slim\PDO\Database;

class Model
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

    public function insert($projet, $table){
        $query = self::$db_connection->insert( array_keys($projet) )
            ->into($table)
            ->values( array_values($projet) );

        $insert_id = $query->execute(false);

        if ($insert_id){
            return $insert_id;
        }else{
            return false;
        }
    }

    public function getDataById($id, $table, $champ){
        $query = self::$db_connection->select()
            ->from($table)
            ->where($champ, '=', $id)
            ->orderby($champ);

        $stmt = $query->execute();
        return $result = $stmt->fetch();
    }
}