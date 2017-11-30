$(document).ready(function(){

    $("#menu-to-block").css('display','block');

    // Datatable matierePremiere

    $('#table-matieres').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );

    $('body').on('DOMNodeInserted','tr.child',function(){
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    });

    $("#table-matieres").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-matieres").attr('data-href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                $(".filter").each(function(){
                    if($(this).val() !== '' && $(this).val() !== null)
                        criteres[$(this).attr('id')]=$(this).val();
                });
                d.criteres = criteres;
            },
            "dataSrc" : function (json) {
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "titre"},
            {"data": "prixAchat"},
            {"data": "fournisseurs"},
            {"data": "options"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,4,5,6]},
            {className: "text-center", "targets": [3]},
            {className: "bg-ligne", "targets": [0]}
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

    // end datatable

    // filtrer la table des matieres

    $(".filter").on('change keyup',function(){
        $("#table-matieres").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-matieres").DataTable().page.len($("#length").val()).draw();
    });


    // end filter


    // afficher le formulaire d'ajout d'une matiere

    $("body").on('click','#add-matiere,.edit-matiere',function(){
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
                    $("#appbundle_produit_matiere").parsley();
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
        if($("#appbundle_produit_matiere").length)
            $("#appbundle_produit_matiere").trigger('submit');
        if($("#appbundle_produit_sub").length)
            $("#appbundle_produit_sub").trigger('submit');
    });

    // end formulaire

    // afficher le formulaire d'ajout d'un produit

    $("body").on('click','#add-produit,.edit-produit,#add-sous-produit,.edit-sous-produit',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {'parent':$this.attr('data-parent')},
            function(json){
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_produit_sub").parsley();
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

    // end formulaire

    // Archiver/Desarchiver une matiere/produit

    $("body").on('click','.matiere-toggle-state,.produit-toggle-state,.sous-produit-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    if($this.hasClass('matiere-toggle-state'))
                        $("#table-matieres").DataTable().ajax.reload();
                    else if($this.hasClass('produit-toggle-state'))
                        getProduits($("#table-productions").attr('data-href'),null);
                    else if($this.hasClass('sous-produit-toggle-state')){
                        var url = $("#table-sous-produits").attr('data-href');
                        getProduits($("#table-productions").attr('data-href'),null);
                        getSousProduits(url,null);
                    }
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

    // Envoyer le formulaire

    $("body").on('submit','#appbundle_produit_matiere',function(e){
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
                    $("#table-matieres").DataTable().ajax.reload();
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

    // End envoie formulaire

    // get liste Produits

    $("body").on('click','.get-productions',function(){
        getProduits($(this).attr('data-href'),$(this));
    });

    /**
     * get liste produits
     */
    function getProduits(href,$btn){
        $("#sous-produits").empty();
        $("#productions").empty();
        if($btn)
            $btn.button('loading');
        $.get(
            href,
            {'table':'produits'},
            function(json){
                if(json.code === 1){
                    $("#productions").html(json.html);
                    $('#table-productions').DataTable({
                        responsive: true,
                        "processing": false,
                        "bFilter": false,
                        "serverSide": false,
                        "pageLength": 50,
                        "bLengthChange":false,
                        'info': false,
                        "order": [[ 2, "asc" ]]
                    });
                }
                if($btn)
                    $btn.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            if($btn)
                $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    }
    // end liste produits

    // Envoyer le formulaire produit

    $("body").on('submit','#appbundle_produit_sub',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        var url = null;
        if($('#table-sous-produits').length)
            url = $("#table-sous-produits").attr('data-href');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $("#small-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    getProduits($("#table-productions").attr('data-href'),null);
                    if(url)
                        getSousProduits(url,null);
                    $("#table-matieres").DataTable().ajax.reload();
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

    // End envoie formulaire

    // supprimer un produit

    $("body").on('click','.delete-matiere,.delete-produit,.delete-sous-produit',function(){
        $this = $(this);
        var url = null;
        if($this.hasClass('delete-sous-produit'))
            url = $("#table-sous-produits").attr('data-href');
        swal({
                title: "Voullez-vous supprimer ce produit ?",
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
                            if($this.hasClass('delete-matiere'))
                                $("#productions,#sous-produits").empty();
                            else if($this.hasClass('delete-produit')){
                                getProduits($("#table-productions").attr('data-href'),null);
                                $("#sous-produits").empty();
                            } else if($this.hasClass('delete-sous-produit') && url){
                                getProduits($("#table-productions").attr('data-href'),null);
                                getSousProduits(url,null);
                            }
                            $("#table-matieres").DataTable().ajax.reload();

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


    // liste sous produits

    /**
     * get liste sous produits
     */
    function getSousProduits(href,$btn){
        $("#sous-produits").empty();
        if($btn)
            $btn.button('loading');
        $.get(
            href,
            {'table':'sous-produits'},
            function(json){
                if(json.code === 1){
                    $("#sous-produits").html(json.html);
                    $('#table-sous-produits').DataTable({
                        responsive: true,
                        "processing": false,
                        "bFilter": false,
                        "serverSide": false,
                        "pageLength": 50,
                        "bLengthChange":false,
                        'info': false
                    });
                }
                if($btn)
                    $btn.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            if($btn)
                $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    }

    $("body").on('click','.get-sous-produits',function(){
        getSousProduits($(this).attr('data-href'),$(this));
    });

    // end sous produita

});