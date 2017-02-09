<?php
/**
 * Affiche le formulaire pour ajouter un nouveau Projet.
 */



if ( $_POST["nomprojet"] && isset($_POST["nomprojet"]) ){

    $projet = [
      "nom" => $_POST["nomprojet"]
    ];

    require_once '../config.inc';

    $last_id = new \Ubeo\Projet($pdo, $projet);
    if ($last_id){
        header('Location: ../index.php');
        exit();
    }
}

?>

<div class="form-flotant">
    <form action="/formulaires/ajoutProjet.php" method="post">
        <input type="text" required name="nomprojet" placeholder="Nom du projet" />
        <input type="submit" value="Créer" />
    </form>
</div>
