

function showFormulaireAjouterNotes( e, task_id ){

    var current_text = ""

    if ( $(e).children().find('.tooltiptext').text() ){

        current_text = $(e).children().find('.tooltiptext').text();
        //console.log(current_text);
    }

    $(e).after(
        '<form method="post" action="classes/Task.php">' +
        '<input type="hidden" name="action" value="modify_notes" />' +
        '<input type="hidden" name="id_task" value="' + task_id +'" />' +
        '<textarea name="notes" class="notes" >' + current_text + '</textarea>' + '' +
        '<button type="submit" >Mettre à jour</button>'+
        '</form>');

}

function showFormulaireAjouterNotesRetard( e, task_id ){

    $(e).after(
        '<form method="post" action="classes/Task.php">'+
        '<input type="hidden" name="action" value="modify_notes" />' +
        '<input type="hidden" name="id_task" value="' + task_id +'" />' +
        '<textarea name="overdue_notes" class="notes" ></textarea>' +
        '<button type="submit" >Mettre à jour</button>' +
        '</form>');

}

function showDatePicker( e, task_id, start_date ){

    $(e).after(
        '<form method="post" action="classes/Task.php">' +
        '<input type="hidden" name="action" value="modify_start_date" />' +
        '<input type="hidden" name="id_task" value="' + task_id + '" />' +
        '<input name="start_date" type="date" value="' + start_date + '" />' +
        '<button type="submit" >Modifier</button>' +
        '</form>'
    );

}

function showAndHideForm( e ){

    $(e).next().toggle();
}


function showFormulaireModifierProjet( id_project, project_name,  start_date) {

    $('.form_fixed').toggle();

    $('.form_fixed').find('h1').text('Modifier un projet');

    if ( $('#id_projet').length == 0){
        $('form.form_projets').append('<input type="hidden" id="id_project" name="id_project" value="'+id_project+'" />');
    }

    $('form.form_projets').find('input[name=nom_projet]').val(project_name);
    $('form.form_projets').find('input[name=action]').val('modify_project');

    if( start_date ){
        date_input = $('form.form_projets').find('input[name=date_livraison]');

        date_input.attr({type:"date"});
        date_input.remove('onfocus');
        date_input.val( start_date );
    }

    $('form.form_projets').find('button').text('Modifier');

}


