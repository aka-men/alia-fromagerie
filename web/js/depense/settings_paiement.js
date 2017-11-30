$(document).ready(function(){

    $("#menu-to-block").css('display','block');

    // liste datatable

    $("#table-modes").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-modes").attr('data-href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                $(".filter").each(function(){
                    if($(this).val() && $(this).val() !== '')
                        criteres[$(this).attr('id')]=$(this).val();
                });
                d.criteres = criteres;
            }
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
            {className: "text-center", "targets": [3]}
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

    // End liste

    // Filtrer la liste des produits

    $(".filter").on('change keyup',function(){
        $("#table-modes").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-modes").DataTable().page.len($("#length").val()).draw();
    });


    // End filtre
    // afficher formulaire

    // Ouvrir le formulaire d'ajout et modification

    $("body").on('click','#add-mode,.edit-mode',function(){
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
                    $.Admin.controle.saisie();
                    $("#appbundle_modepayment").parsley();
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
        if($("#appbundle_modepayment").length)
            $("#appbundle_modepayment").trigger('submit');
    });

    // End formulaire

    // envoie formulaire

    $("body").on('submit','#appbundle_modepayment',function(e){
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
                    $("#table-modes").DataTable().ajax.reload();
                }else if(json.code === 0){
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


    // end envoie


    // Archiver/Desarchiver un produit

    $("body").on('click','.mode-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    $("#table-modes").DataTable().ajax.reload();
                }
                $this.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    // End Archive

    // supprimer un produit

    $("body").on('click','.delete-mode',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer ce mode ?",
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
                            $("#table-modes").DataTable().ajax.reload();
                        }else if(json.code === 0){
                            swal(json.msg);
                        }
                        $this.button('reset');
                    },
                    "JSON"
                ).fail(function ($xhr) {
                    $this.button('reset');
                    swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                });
            });
    });

    // end suppression

});