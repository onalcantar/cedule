<?php

require_once 'config.php';
require_once 'vendor/autoload.php';

define("DB_DATABASE", "cedulede_cedule");
define("DB_HOST", "127.0.0.1");
define("DB_USER", "cedulede_admin");
define("DB_PASSWORD", "B0{O3tSZKJSZ");

$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8';
$usr = DB_USER;
$pwd = DB_PASSWORD;

$pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);
