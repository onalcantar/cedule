<?php
/**
 * Author : Ubéo Solutions Web
 * Page d'affichage par défaut pour effectuer le suivi des projets
 * Utiliser la variable $database comme objet de DB
 *
 */
setlocale(LC_TIME, "fr_CA");
require_once 'db/BD.php';
require_once 'functions/Projet.php';
require_once 'functions/Tache.php';

//Création du formulaire d'ajout des projets
//require_once "formulaires/ajoutProjet.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Cédule | Gestionnaire de projets Ubéo</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/cedule.css" >

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous">
    </script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-1"></div>
            <?php
                $projets = $projet->getProjetTitles();
                foreach ($projets as $project){
                    print "<div class='col-md-1'>".$project['nom']."</div>";
                }
            ?>
        </div>
    </div>


<?php

    $datepluspetite = strtotime($projet->getDatePlusPetite());
    $dateplusgrande = strtotime($projet->getDatePlusGrande());

    //1 pour lundi 7 pour dimanche//
    $joursemaine = date( 'N' , $datepluspetite);

    if ($joursemaine == 1){
        $dernierlundi = $datepluspetite;
    }else{
        $dernierlundi = strtotime('previous monday', $datepluspetite);
    }

    $datetoprint = '';

    //Sem. '. utf8_encode(strftime('%e %b', $dernierlundi));
    while(date('Y-m-d', $dernierlundi) <= date('Y-m-d', $dateplusgrande)){

        print '<div class="row">';
            print '<div class="col-md-12 pre" id="'.date("Y-m-d", $dernierlundi).'">';
            for ($i = 0; $i <5; $i++){
                //On rajoute un jour
                if ($i > 0){
                    $dernierlundi = $dernierlundi + (60 * 60 * 24);
                }else{
                    $datetoprint = 'Sem. '. strftime('%e %b', $dernierlundi);
                }

                print '<div class="row">';
                for ($x = 0; $x < 12; $x++){

                    $id = date("Y-m-d", $dernierlundi). "-".$x;
                    print '<div class="col-md-1" id="'.$id.'">';
                    print $datetoprint !== '' ? $datetoprint : '';
                    print '</div>';
                }
                print '</div>';

                $datetoprint = '';
            }
            print '</div>';
        print '</div>';

        print '<div class="row">';
            print '<div class="col-md-12">';
            for ($i = 0; $i < 2; $i++){
                //On rajoute un jour
                $dernierlundi = $dernierlundi + (60 * 60 * 24);

                print '<div class="row">';
                for ($x = 0; $x < 12; $x++){

                    $id = date("Y-m-d", $dernierlundi). "-".$x;
                    print '<div class="col-md-1" id="'.$id.'">';
                    print '</div>';
                }
                print '</div>';
            }
            print '</div>';
        print '</div>';

        $dernierlundi = $dernierlundi + (60 * 60 * 24 * 1);
    }

    $taches  = $projet->getTacheDetailsByProjet(1);

    foreach ($taches as $tache){

        $style = $tacheobjet->getTacheStyle($tache, 1);
        print '<div class=".col-md-1" style="'.$style.'">'.$tache["nom"].'</div>';
    }

?>
</div>
</body>
</html>
