<?php

$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_DATABASE.';charset=utf8';
$usr = DB_USER;
$pwd = DB_PASSWORD;

$pdo = new \Slim\PDO\Database($dsn, $usr, $pwd);
