

function onTacheTerminee(id_tache) {

    $.ajax({ url: '../actions/TacheActions.php',
        data: {action: 'updateStatusTache', id_tache : id_tache},
        type: 'post',
        success: function(output) {
            if (output == "1"){
                location.reload();
            }
        }
    })
}

function showFormAjouterTache(){

}
