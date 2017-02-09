<?php

?>

<div class="form-tache-flotant">
    <form method="post" action="ajoutTache.php">

        <div class="row">
            <label>Nom du Projet</label>
            <input type="text" name="nomtache" placeholder="Entrez le nom de la tâche" required />
        </div>

        <div class="row">
            <label>Nom de tâche</label>
            <input type="text" name="nomtache" placeholder="Entrez le nom de la tâche" required />
        </div>

        <div class="row">
            <label>Date de début</label>
            <input type="date" name="datedebut" placeholder="Entrez la date de début" required />
        </div>

        <div class="row">
            <label>Durée (en semaines)</label>
            <select name="duree">
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
            <label>Notes</label>
            <textarea name="notes" placeholder="Entrez vos notes de tâche"></textarea>
        </div>

        <button type="submit" name="creer" value="Créer" ></button>
    </form>
</div>
