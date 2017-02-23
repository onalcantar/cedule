

function showFormulaireAjouterNotes( e, task_id ){

    var current_text = ""

    if ( $(e).children().find('.tooltiptext').text() ){

        current_text = $(e).children().find('.tooltiptext').text();
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
        '<form id="date_picker_form" method="post" action="classes/Task.php">' +
        '<input type="hidden" name="action" value="modify_start_date" />' +
        '<input type="hidden" name="id_task" value="' + task_id + '" />' +
        '<input name="start_date" type="date" value="' + start_date + '" />' +
        '<button type="submit" >Modifier</button>' +
        '</form>'
    );

}

var parentData = {};

$(document).ready(function() {
    $(".fancybox").fancybox();

    //var parentData = {};

    $(".various").fancybox({
        maxWidth	: 800,
        maxHeight	: 600,
        fitToView	: false,
        width		: '70%',
        height		: '70%',
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none',
        beforeLoad : function () {
            parentData.id = $(this.element).data("id");
            parentData.name = $(this.element).data("name");
            parentData.date = $(this.element).data("date");
        },
        afterClose : function () {
            location.reload();
        }
    });


    $('form.task_form').dblclick( function() {
        console.log("ici");
        $.fancybox({
            href: '../formulaires/addTask.php',
            type: 'iframe',
            maxWidth	: 800,
            maxHeight	: 600,
            fitToView	: false,
            width		: '70%',
            height		: '70%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none',
            beforeLoad : function () {
                parentData.id = $(this.element).data("id");
                parentData.name = $(this.element).data("name");
                parentData.date = $(this.element).data("date");
            },
            afterClose : function () {
                location.reload();
            }
            // other options here
        });
    });
});


