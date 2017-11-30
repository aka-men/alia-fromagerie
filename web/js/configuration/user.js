/**
 * Created by abdelhak on 10/04/2017.
 */

$(document).ready(function(){
    $("#table-users").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-types").attr('data-href'),
            "type": "POST"
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "username"},
            {"data": "email"},
            {"data": "role"},
            {"data": "lastLogin"},
            {"data": "active"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: 0},
            {className: "bg-ligne", "targets": 0},
            {className: "text-center", "targets": [6,5,7]}
        ],
        "order": [[ 2, "asc" ]],
        "pageLength": 50,
        "bLengthChange":false,
        "language": {
            "sProcessing": "Traitement en cours...",
            "sSearch": "Rechercher&nbsp;:",
            "sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
            "sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            "sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            "sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            "sInfoPostFix": "",
            "sLoadingRecords": "Chargement en cours...",
            "sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
            "sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
            "oPaginate": {
                "sFirst": "Premier",
                "sPrevious": "Pr&eacute;c&eacute;dent",
                "sNext": "Suivant",
                "sLast": "Dernier"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
            }
        }
    });
    $("body").on('click','.toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {'id_user':$this.attr('data-id')},
            function(json){
                $this.button('reset');
                if(json.code===1){
                    swal("Succès", "Le statut de l'utilisateur été changée avec succès!", "success");
                    $("#table-users").DataTable().ajax.reload();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','#add-user,.edit-user',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.controle.saisie();
                    $.Admin.select.activate();
                    $("#appbundle_user").parsley();
                    $("#small-modal").modal('show');
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','div#small-modal #submit',function(){
        if($("#appbundle_user").length)
            $("#appbundle_user").trigger('submit');
    });

    $("body").on('submit','#appbundle_user',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $("#small-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    $("#table-users").DataTable().ajax.reload();
                }else  if(json.code === 0){
                    $this.closest('div.modal').find('p.form-error').html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

});