<?php
setlocale(LC_TIME, "fr_CA");
require_once 'db/BD.php';
require_once 'classes/Projet.php';

$objet_projet = new classes\Projet\Projet($pdo, 1);

echo $objet_projet->getNom();
