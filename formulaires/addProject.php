<?php

    session_start();
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

<div class="">

    <h1>Ajouter un projet</h1>

    <form method="post" action="../classes/Project.php" class="form_projets" >
        <input type="hidden" name="action" value="add_project" />

        <input type="text" id="project_name" name="project_name" value="" placeholder="Nom du projet" required /><br>
        <input placeholder="Date de livraison" id="delivery_date" name="delivery_date" class="textbox-n" type="text" onfocus="(this.type='date', this.value='<?php echo date('Y-m-d'); ?>' )"  /><br>
        <input type="checkbox" name="date_confirmed" value="confirmed">Cochez si le projet n'a pas de date de livraison<br>
        <button type="submit">Ajouter</button>

    </form>

</div>