<?php
/**
 * Affiche le formulaire pour ajouter un nouveau Projet.
 */

$sqlSelect = "SELECT nom FROM Projet";
$data = $database->getQuery($sqlSelect);
foreach($data as $d)
{
    echo $d["nom"]." ".$d["datedebut"];
}

if (isset($_POST["nomprojet"]) && $_POST["nomprojet"]){
    $sql = "INSERT INTO Projet SET nom = ";
}

?>

<form action="ajoutProjet.php" method="post">
    <input type="text" name="nomprojet" placeholder="Nom du projet" />
    <input type="date" name="datedebut" placeholder="Date de début" />
    <input type="submit" value="Créer" />
</form>
