<?php
/**
 * Author : Ubéo Solutions Web
 * Page d'affichage par défaut pour effectuer le suivi des projets
 * Utiliser la variable $database comme objet de DB
 *
 */

require_once 'config.inc';
$projects = $helper::getIdsProjects($db);

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
    if (isset($projects)){
        foreach ($projects as $id_project){
            $project = new \Ubeo\Project( $id_project["id_project"] );
            $project->printTitle();
            $project->printTasks();
        }
    }

    ?>
</div>

<?php
    $helper::printSemaines( $db );
?>


<a class="toogle_form" onclick="showAndHideForm( this )"><i>X</i></a>
<div class="form_fixed add_projet">

    <h1>Ajouter un projet</h1>

    <form method="post" action="classes/Project.php" class="form_projets" >
        <input type="hidden" name="action" value="add_project" />

        <input type="text" name="project_name" value="" placeholder="Nom du projet" required />
        <input placeholder="Date de livraison" name="delivery_date" class="textbox-n" type="text" onfocus="(this.type='date', this.value='<?php echo date('Y-m-d'); ?>' )"  id="date">
        <button type="submit">Ajouter</button>

    </form>
</div>


<a class="toogle_form_projet" onclick="showAndHideForm( this )"><i>X</i></a>
<div class="form_fixed_projet add_tache">

    <h1>Ajouter une tâche</h1>

    <form method="post" action="classes/Task.php" class="form_taches" >
        <input type="hidden" name="action" value="ajouter_tache" />

        <input type="text" name="nom_tache" value="" placeholder="Nom de la tâche" required />
        <input placeholder="Date de debut" name="date_debut" class="textbox-n" type="text" onfocus="(this.type='date', this.value='<?php echo date('Y-m-d'); ?>' )"  id="date">
        <input type="number" placeholder="Durée (en semaines)" name="duree" value="1" />
        <textarea name="notes" placeholder="Notes"></textarea>
        <input type="checkbox" name="terminee" value="">Terminée
        <select name="id_projet">
            <option name="1" value="1">Ubeo</option>
        </select>
        <button type="submit">Ajouter</button>

    </form>
</div>

</body>
</html>