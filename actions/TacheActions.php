<?php


if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'updateStatusTache' : updateStatusTache();break;
    }
}

function updateStatusTache(){
    $id_tache = isset($_POST['id_tache']) ? $_POST['id_tache'] : "";

    if ($id_tache != ""){


    }
}