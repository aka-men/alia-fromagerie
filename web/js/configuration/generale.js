/**
 * Created by abdelhak on 17/06/2017.
 */

$(document).ready(function(){

    $("body").on('submit','#config_entreprise',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.find('button[type=submit]');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1)
                    swal("Succès",json.msg, "success");
                else  if(json.code === 0)
                    $this.closest('div.x_content').find('p.form-error').html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('change','#config_admin_admin_enable_retour',function(){
             if($(this).is(":checked"))
                 $("#config_admin_admin_code_retour").removeAttr('disabled').attr('required','required').focus();
             else
                 $("#config_admin_admin_code_retour").val('').prop('disabled','disabled').removeAttr('required');
             $("#config_admin").parsley();
        $("#config_admin").parsley().validate();
    });

    $("body").on('submit','#config_admin',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.find('button[type=submit]');
        var donnes = new FormData();
        $this.find('select[name],input[name],textarea[name]').each(function(){
            if ($(this).is('input:file') && $(this)[0].files.length > 0)
                donnes.append($(this).attr('name'), $(this)[0].files[0]);
            else if ($(this).is('input:checkbox'))
                donnes.append($(this).attr('name'), $(this).is(":checked") ? true : false);
            else
                donnes.append($(this).attr('name'), $(this).val());
        });
        $btn.button('loading');
        $.ajax({
            url: $this.attr('action'),
            type: 'POST',
            dataType: "json",
            data: donnes,
            cache: false,
            contentType: false,
            processData: false,
            success: function(json){
                if(json.code === 1) {
                    swal("Succès",json.msg,"success");
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });


    $("body").on('click','.bind_img',function(e){
        e.preventDefault();
        $(this).closest('div.form-group').find('input:file').trigger('click');
    });

    $("body").on('change','input:file',function(){
        if(this.files.length === 0){
            $(this).closest("div.form-group").append('<input type="hidden" name="'+$(this).attr('name')+'_deleted" value="null" />');
            $(this).closest('div.form-group').find('img').attr('src',$(this).attr('data-src'));
        }
        else{
            $(this).closest('div.form-group').find('img').attr('src',URL.createObjectURL(this.files[0]));
            $(this).closest("div.form-group").find('input[type=hidden]').remove();
        }
    });

    $("body").on('submit','#config_application',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.find('button[type=submit]');
        var donnes = new FormData();
        donnes.append('config_application[facture_entete]',$("#facture_entete").is(":checked") ? true : false);
        donnes.append('config_application[facture_pied]',$("#facture_pied").is(":checked") ? true : false);
        $this.find("input[type=file]").each(function(index){
            if( $(this)[0].files.length > 0)
                donnes.append('config_application['+$(this).attr('name')+']', $(this)[0].files[0]);
            else if($(this).closest('div.form-group').find('input[type=hidden]').length){
                donnes.append('config_application['+$(this).attr('name')+']',null);
            }
        });
        $btn.button('loading');
        $.ajax({
            url: $this.attr('action'),
            type: 'POST',
            dataType: "json",
            data: donnes,
            cache: false,
            contentType: false,
            processData: false,
            success: function(json){
                if(json.code === 1) {
                    swal("Succès",json.msg,"success");
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });


    $("#table-types").DataTable({
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
            {"data": "label"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3]},
            {className: "bg-ligne", "targets": 0},
        ],
        "order": [[ 2, "asc" ]],
        "pageLength": 5,
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

    $("body").on('click','#add-type,.edit-type',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {"forEmploye":true},
            function(json){
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $("#appbundle_typedepense").parsley();
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
        if($("#appbundle_typedepense").length)
            $("#appbundle_typedepense").trigger('submit');
    });

    // End formulaire

    // envoie formulaire

    $("body").on('submit','#appbundle_typedepense',function(e){
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
                    $("#table-types").DataTable().ajax.reload();
                }else if(json.code === 0)
                    $this.closest('div.modal').find('p.form-error').html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-type,.sous-type-delete',function(){
        $this = $(this);
        $btn = $this.closest('div.btn-group').find('button.dropdown-toggle');
        swal({
                title: "Voullez-vous supprimer cette designation ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "Oui, supprimer!",
                closeOnConfirm: true
            },
            function(){
                $btn.button('loading');
                $.post(
                    $this.attr('data-href'),
                    {},
                    function(json){
                        if(json.code === 1){
                            swal("Succès",json.msg, "success");
                            $("#table-types").DataTable().ajax.reload();
                        }else if(json.code === 0){
                            swal(json.msg);
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

    $("body").on('click','.type-toggle-state',function(){
        $this = $(this);
        $btn = $this.closest('div.btn-group').find('button.dropdown-toggle');
        $btn.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    if($this.hasClass('sous-type-toggle-state'))
                        getSousTypes($("#table-sous-types").attr('data-href'),null);
                    else
                        $("#table-types").DataTable().ajax.reload();
                }
                $btn.button('loading');
            },
            'JSON'
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("#table-types-mensuelles").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-types-mensuelles").attr('data-href'),
            "type": "POST"
        },
        "columns": [
            {"data": "ligne"},
            {"data": "label"}
        ],
        columnDefs: [
            {orderable: false, targets: [0]},
            {className: "bg-ligne text-center", "targets": 0},
            { "width": "20px", "targets": 0 }
        ],
        "order": [[ 1, "asc" ]],
        "pageLength": 5,
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


    $("body").on('click','#select-type-mensuelle',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.form);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $("#types_mensuelles").parsley();
                    $("#medium-modal").modal('show');
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','div#medium-modal #submit',function(){
        if($("#types_mensuelles").length)
            $("#types_mensuelles").trigger('submit');
    });

    $("body").on('submit','#types_mensuelles',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $("#medium-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    $("#table-types-mensuelles").DataTable().ajax.reload();
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
