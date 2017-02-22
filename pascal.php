<?php
/**
 * Created by PhpStorm.
 * User: pascalmercier
 * Date: 17-02-03
 * Time: 15:03
 */

setlocale(LC_TIME, "fr_CA");
require_once 'db/DB.php';
require_once 'classes/Task.php';

$tache = new Tache($pdo, 1);

/*
 *  $this->setIdTache($data['id_tache']);
 		    $this->setNom($data['nom']);
 	        $this->setDateDebut($data['date_debut']);
 	        $this->setDuree($data['duree']);
 	        $this->setMaitre($data['maitre']);
 	        $this->setNotes($data['notes']);
 	        $this->setRetard($data['retard']);
 	        $this->setTermine($data['termine']);
 */

$tache2 = new Tache($pdo,
	array(
		'nom' => 'Tache test',
		'date_debut' => '2017-03-23',
		'duree' => 3,
		'maitre' => 0,
		'notes' => '',
		'retard' => '',
		'termine' => 0,
		'id_projet' => 2,
	) );

echo $tache2->getIdTache();