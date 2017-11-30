/**
 * Created by abdelhak on 26/05/2017.
 */


$(document).ready(function(){

    if($("#table-clients thead tr:first th").length === 8){
        var columns =[
            {"data": "ligne"},
            {"data": "id"},
            {"data": "nom"},
            {"data": "cin"},
            {"data": "email"},
            {"data": "gsm"},
            {"data": "entreprise"},
            {"data": "action"}
        ];
        var columnDefs = [
            {orderable: false, targets: [0,6,7]},
            {className: "text-center", "targets": 6},
            {className: "bg-ligne", "targets": 0}
        ];
    }else if ($("#table-clients thead tr:first th").length === 7){
        var columns =[
            {"data": "ligne"},
            {"data": "id"},
            {"data": "nom"},
            {"data": "cin"},
            {"data": "email"},
            {"data": "gsm"},
            {"data": "action"}
        ];
        var columnDefs = [
            {orderable: false, targets: [0,6]},
            {className: "bg-ligne", "targets": [0]}
        ];
    }

    $("#table-clients").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info": false,
        "createdRow": function ( row, data, index ) {
            if (data.isEmploye) {
                $(row).css({
                    'background-color': 'lightgoldenrodyellow'
                });
            }
        },
        "ajax": {
            "url": $("#table-clients").attr('data-href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                $(".filter").each(function(){
                    if($(this).val() !== '')
                        criteres[$(this).attr('id')]=$(this).val();
                });
                d.criteres = criteres;
            },
            "dataSrc" : function (json) {
                return json.data;
            }
        },
        "columns": columns,
        "columnDefs": columnDefs,
        "order": [[ 2, "asc" ]],
        "pageLength": $("#length").val(),
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

    $(".filter").on('change keyup',function(){
        $("#table-clients").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-clients").DataTable().page.len($("#length").val()).draw();
    });

    $("body").on('click','#add-client,.edit-client,#import-client',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {'type':$this.attr('data-type')},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.form);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.controle.saisie();
                    $.Admin.checkInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_client").parsley();
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
        if($("#appbundle_client").length)
            $("#appbundle_client").trigger('submit');
        if( $("#client_produits").length)
            $("#client_produits").trigger('submit');
    });

   $("body").on('click','div#small-modal #submit',function(){
       if($("#client_entreprise").length)
           $("#client_entreprise").trigger('submit');
    });


    $("body").on('submit','#appbundle_client',function(e){
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
                    $("#table-clients").DataTable().ajax.reload();
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

    $("body").on('click','.client-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    $("#table-clients").DataTable().ajax.reload();
                }
                $this.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-client',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer ce client ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "Oui, supprimer!",
                closeOnConfirm: true
            },
            function(){
                $this.button('loading');
                $.post(
                    $this.attr('data-href'),
                    {},
                    function(json){
                        if(json.code === 1){
                            swal("Succès",json.msg, "success");
                            $("#table-clients").DataTable().ajax.reload();
                        }else if(json.code === 0)
                            swal(json.msg);
                        $this.button('reset');
                    },
                    'JSON'
                ).fail(function ($xhr) {
                    $this.button('reset');
                    swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                });
            });
    });

    $("body").on('click','.client-atach-company',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $("#client_entreprise").parsley();
                    $("#small-modal").modal('show');
                    $("#entreprise_id").parsley().on('field:error', function() {
                        $("#client_entreprise").find('ul.parsley-errors-list').css('display','none');
                        $("#entreprise_id").closest('div.form-group').find('button[data-id=entreprise_id]').css({
                            'background': '#FAEDEC',
                            'border':'1px solid #E85445'
                        });
                    });
                    $("#entreprise_id").parsley().on('field:success', function() {
                        $("#entreprise_id").closest('div.form-group').find('button[data-id=entreprise_id]').css({
                            'background': '',
                            'border':''
                        });
                    });
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('submit','#client_entreprise',function(e){
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
                    $("#table-clients").DataTable().ajax.reload();
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


    $("body").on('click','#add-entreprise',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.controle.saisie();
                    $.Admin.select.activate();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_entreprise").parsley();
                    $("#small-modal").modal('hide');
                    $("#large-modal").modal('show');
                    $('#large-modal').on('hidden.bs.modal', function (e) {
                        $("#small-modal").modal('show');
                    });
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','div#large-modal #submit',function(){
       if( $("#appbundle_entreprise").length)
           $("#appbundle_entreprise").trigger('submit');
    });

    $("body").on('submit','#appbundle_entreprise',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $("#large-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    var entreprise = $.parseJSON(json.entreprise);
                    var option = new Option(entreprise.nom,entreprise.id,true,true);
                    $("#entreprise_id").append(option);
                    $.Admin.select.refresh();
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

    $("body").on('click','button.produits-client',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.form);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.controle.saisie();
                    autosize($('textarea.auto-growth'));
                    $("#client_produits").parsley();
                    $("#medium-modal").modal('show');
                    $('.checkOneOthers,.checkOneAlia').trigger('change');
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });


    $("body").on('submit','#client_produits',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                $("#medium-modal").modal("hide");
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                }else  if(json.code === 0) {
                    swal("Erreur", json.msg, "error");
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
