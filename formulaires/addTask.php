<?php

    include_once '../config.inc';
    $projects = $helper::getProjects($db);



?>

<script type="text/javascript" src="../assets/js/jquery.min.js"></script>

<!-- FancyBox -->
<link rel="stylesheet" href="../assets/fancybox/source/jquery.fancybox.css?v=2.1.6" type="text/css" media="screen" />
<!-- FancyBox -->

<?php
if ( isset($_SESSION["message"]) ){
	unset($_SESSION["message"]); ?>

    <script>
        parent.jQuery.fancybox.close();
    </script>
	<?php
} ?>

<div>
    <h1>Ajouter une tâche</h1>
    <form method="post" action="../classes/Task.php">

        <input type="hidden" name="action" value="add_task" />

        <div class="row">
            <label>Nom du Projet</label><br>
            <select name="project" required>
                <?php foreach ($projects as $project){
                    echo '<option name="'.$project["project_name"].'" value="'.$project["id_project"].'" >'.$project["project_name"].'</option>';
                } ?>
            </select>
        </div>

        <div class="row">
            <label>Nom de tâche</label><br>
            <input type="text" name="task_name" placeholder="Entrez le nom de la tâche" required />
        </div>

        <div class="row">
            <label>Date de début (Juste pour la première tâche du projet)</label><br>
            <input type="date" name="start_date" placeholder="Entrez la date de début" />
        </div>

        <div class="row">
            <label>Durée (en semaines)</label><br>
            <select name="duration" required>
                <option name="1" value="1">1</option>
                <option name="2" value="2">2</option>
                <option name="3" value="3">3</option>
                <option name="4" value="4">4</option>
                <option name="5" value="5">5</option>
                <option name="6" value="6">6</option>
                <option name="7" value="7">7</option>
                <option name="8" value="8">8</option>
                <option name="9" value="9">9</option>
                <option name="10" value="10">10</option>
            </select>
        </div>

        <div class="row">
            <label>Notes</label><br>
            <textarea name="notes" placeholder="Entrez vos notes de tâche"></textarea>
        </div>

        <div class="row">
            <label>Cochez si la tâche est déjà terminée</label><br>
            <input type="checkbox" name="completed" value="completed" />
        </div>

        <div class="row">
            <br>
            <button type="submit">Ajouter</button>
        </div>


    </form>
</div>
