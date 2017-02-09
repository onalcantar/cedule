<?php
/**
 * Author : Ubéo Solutions Web
 * Page d'affichage par défaut pour effectuer le suivi des projets
 * Utiliser la variable $database comme objet de DB
 *
 */

require_once 'config.inc';
global $taches_helper;
$taches_helper = new \Ubeo\TachesHelper();
//$projects = $taches_helper::getIdProjects();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ubéo | Cédule</title>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Ubeo - www.ubeo.ca"/>

    <meta name="viewport" id="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" media="all" />
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/style.css" />

    <meta property="og:url" content="http://www.ubeo.ca" />
    <meta property="og:type" content="company" />
    <meta property="og:title" content="Ubeo" />
    <meta property="og:image" content="http://www.ubeo.ca/images/facebook_share.png" />
    <meta property="og:description" content="Description du site" />

    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.easing.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.localScroll.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.scrollTo.min.js"></script>
    <script type="text/javascript" src="assets/js/projet.functions.js"></script>
    <script>
        $(function(){
            $.localScroll();
            $(window).scroll(function(){
                if($(window).scrollTop() > 640){
                    $('#btn_top').show(180);
                }else{
                    $('#btn_top').hide(180);
                }
            });
        });
    </script>
</head>
<body>
<div id="top_page">

</div>
<a id="btn_top" href="#top_page"></a>

<div id="weeks">
    <?php

    $projet_controller = new \Ubeo\ProjetController();
    $projet_controller->printAllTaches();

    /*if (isset($projects)){
        foreach ($projects as $id_projet){
            $number_of_taches = $taches_helper::countNumberOfTachesParProjet($id_projet["id_projet"]);
            $project = new Ubeo\Projet($id_projet["id_projet"]);
            $project->printTitle();
            $project->createTaches($number_of_taches);
        }
    }*/

    ?>
</div>

<?php
    $taches_helper::printSemaines();
?>


</body>
</html>