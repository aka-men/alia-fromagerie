/**
 * Created by abdelhak on 27/05/2017.
 */

$(document).ready(function(){

    $("#table-entreprises").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-entreprises").attr('data-href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                $(".filter").each(function(){
                    if($(this).val() !== '')
                        criteres[$(this).attr('id')]=$(this).val();
                });
                d.criteres = criteres;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "nom"},
            {"data": "email"},
            {"data": "phone"},
            {"data": "fax"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,6]},
            {className: "bg-ligne", "targets": 0}
        ],
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
        $("#table-entreprises").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-entreprises").DataTable().page.len($("#length").val()).draw();
    });

    $("#table-entreprises").on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );

    $('body').on('DOMNodeInserted','tr.child',function(){
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    });

    $("body").on('click','#add-entreprise,.edit-entreprise',function(){
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
                    $.Admin.checkInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_entreprise").parsley();
                    $("#large-modal").modal('show');
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
        if($("#appbundle_entreprise").length)
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
                    $("#table-entreprises").DataTable().ajax.reload();
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

    $("body").on('click','.entreprise-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    $("#table-entreprises").DataTable().ajax.reload();
                }
                $this.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-entreprise',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer cette entreprise ?",
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
                            $("#table-entreprises").DataTable().ajax.reload();
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


    $("body").on('click','button.produits-entreprise',function(){
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
                    $("#entreprise_produits").parsley();
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

    $("body").on('click','div#medium-modal #submit',function(){
        if($("#entreprise_produits").length)
            $("#entreprise_produits").trigger('submit');
    });

    $("body").on('submit','#entreprise_produits',function(e){
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