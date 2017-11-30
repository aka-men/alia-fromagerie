$(document).ready(function(){

    $("#menu-to-block").css('display','block');

// Liste fournisseur DataTable

    $('#table-fournisseurs-other,#table-fournisseurs-MP,#table-fournisseurs-PI').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );

    $('body').on('DOMNodeInserted','tr.child',function(){
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    });

    $("#table-fournisseurs-other").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-fournisseurs-other").attr('data-href'),
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
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3]},
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

    $("#table-fournisseurs-MP").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-fournisseurs-MP").attr('data-href'),
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
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3]},
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

    $("#table-fournisseurs-PI").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-fournisseurs-PI").attr('data-href'),
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
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3]},
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

    // End liste

    // Filtrer la liste des fournisseurs

    $(".filter").on('change keyup',function(){
        $("#table-fournisseurs-other").DataTable().ajax.reload();
        $("#table-fournisseurs-MP").DataTable().ajax.reload();
        $("#table-fournisseurs-PI").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-fournisseurs-other").DataTable().page.len($("#length").val()).draw();
        $("#table-fournisseurs-MP").DataTable().page.len($("#length").val()).draw();
        $("#table-fournisseurs-PI").DataTable().page.len($("#length").val()).draw();
    });


    // End filtre

    // Ouvrir le formulaire d'ajout et modification

    $("body").on('click','#add-fournisseur,.edit-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {
                'typeVente':$this.attr('data-type-vente')
            },
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_fournisseur").parsley();
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
        if( $("#appbundle_fournisseur").length)
            $("#appbundle_fournisseur").trigger('submit');
    });

    // End formulaire


    // Archiver/Desarchiver un fournisseur

    $("body").on('click','.fournisseur-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    $("#table-fournisseurs-other").DataTable().ajax.reload();
                    $("#table-fournisseurs-MP").DataTable().ajax.reload();
                    $("#table-fournisseurs-PI").DataTable().ajax.reload();
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

    $("body").on('submit','#appbundle_fournisseur',function(e){
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
                    $("#table-fournisseurs-other").DataTable().ajax.reload();
                    $("#table-fournisseurs-MP").DataTable().ajax.reload();
                    $("#table-fournisseurs-PI").DataTable().ajax.reload();
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


    /**
     * Get types pour un fournisseur
     */
    function getTypes(href,$btn){
        $("#sous-types").empty();
        $("#types").empty();
        if($btn)
            $btn.button('loading');
        $.get(
            href,
            {},
            function(json){
                if(json.code === 1){
                    $("#types").html(json.html);
                    $('#table-types').DataTable({
                        responsive: true,
                        "processing": false,
                        "bFilter": false,
                        "serverSide": false,
                        "pageLength": 50,
                        "bLengthChange":false,
                        'info': false,
                        "order": [[ 1, "asc" ]]
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

    /**
     * Get sous-types pour un type
     */
    function getSousTypes(href,$btn){
        $("#sous-types").empty();
        if($btn)
            $btn.button('loading');
        $.get(
            href,
            {},
            function(json){
                if(json.code === 1){
                    $("#sous-types").html(json.html);
                    $('#table-sous-types').DataTable({
                        responsive: true,
                        "processing": false,
                        "bFilter": false,
                        "serverSide": false,
                        "pageLength": 50,
                        "bLengthChange":false,
                        'info': false,
                        "order": [[ 1, "asc" ]]
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


    // Afficher la liste des types pour un fournisseur

    $("body").on('click','.fournisseur-get-types',function(){
        getTypes($(this).attr('data-href'),$(this));
    });

    // end liste types pour un fournisseur


    //  Afficher formulaire pour ajouter une designation pour un fournisseur
    var index = 0;
    $("body").on('click','#add-type-to-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {
                'fournisseur':$this.attr('data-fournisseur'),
                'target':'getTypes'
            },
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $("#small-modal").modal('show');
                    index = 0;
                    $('.add-line-type').trigger('click');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    // end formulaire

    // Ajouter une ligne type

    $("body").on('click','.add-line-type',function(e){
        e.preventDefault();
        $this = $(this);
        var template = $this.attr('data-prototype').replace(/__name__/g,index);
        var $prototype = $(template);
        $("#types-collection").append($prototype);
        $("#appbundle_typedepense").parsley();
        index++;
        if( $("#types-collection").find('div.input-group').length === 1)
            $("#types-collection").find('button.delete-line-type').prop('disabled',true);
        else
            $("#types-collection").find('button.delete-line-type').prop('disabled',false);
    });

    // End ajout ligne

    // Supprimer une ligne type

    $("body").on('click','.delete-line-type',function(e){
        e.preventDefault();
        $(this).closest('div.col-lg-12').remove();
        $("#appbundle_typedepense").parsley();
        if( $("#types-collection").find('div.input-group').length === 1)
            $("#types-collection").find('button.delete-line-type').prop('disabled',true);
        else
            $("#types-collection").find('button.delete-line-type').prop('disabled',false);
    });

    // End suppression ligne

    // associer le click du bouton au submit du formulaire

    $("body").on('click','div#small-modal #submit',function(){
        if($("#appbundle_typedepense").length)
            $("#appbundle_typedepense").trigger('submit');
        if( $("#appbundle_produit_fini").length)
            $("#appbundle_produit_fini").trigger('submit');
        if( $("#appbundle_produit_matiere").length)
            $("#appbundle_produit_matiere").trigger('submit');
    });

    // end association

    // envoyer le formulaire d'ajout designation pour fournisseur

    window.Parsley.on('form:error', function() {
        if(this.$element.selector === '#appbundle_typedepense'){
            $(this.$element.selector).find('ul.parsley-errors-list').css('position','absolute');
        }
    });

    $("body").on('submit','#appbundle_typedepense',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        var target = $this.find('input#target').val();
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $("#small-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    if(target === 'getTypes')
                        getTypes($("#table-types").attr('data-href'),null);
                    else if(target === 'getSousTypes')
                        getSousTypes($("#table-sous-types").attr('data-href'),null);
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


    // Lister sous libelle pour un type

    $("body").on('click','.list-sous-type',function(){
        getSousTypes($(this).attr('data-href'),$(this));
    });

    // end list

    //  Afficher formulaire pour ajouter des sous libelle pour un type
    var index = 0;
    $("body").on('click','#add-type-to-parent',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {
                'parent':$this.attr('data-parent'),
                'target':'getSousTypes'
            },
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $("#small-modal").modal('show');
                    index = 0;
                    $('.add-line-type').trigger('click');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    // end formulaire

    // supprimer un sous libelle

    $("body").on('click','.sous-type-delete',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer cette designation ?",
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
                            getSousTypes($("#table-sous-types").attr('data-href'),null);
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

    // detacher une designation pour fourniseeur

    $("body").on('click','.type-fournisseur-delete',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    getTypes($("#table-types").attr('data-href'),null);
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

    // end detach


    // Afficher la liste des types

    $('body').on('click','#atache-detache-types-to-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.types);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $("#ms-select-types").css('margin','auto');
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

    // end liste

    // Afficher la liste des produits

    $('body').on('click','#atache-detache-produits-to-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.types);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $('#select-produits').multiSelect({});
                    $("#ms-select-produits").css('margin','auto');
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

    // end liste


    // Envoyer les types/produits selectionnes

    $("body").on('click','div#medium-modal #submit',function(){
        $this = $(this);
        $this.button('loading');
        $data={};
        var url = '';
        var fonction = '';
        if($('#select-types').length){
            $data['types']=$('#select-types').val();
            url = $('#select-types').attr('data-href');
            fonction = 'getTypes';
        }else if($('#select-produits').length){
            $data['produits']=$('#select-produits').val();
            url = $('#select-produits').attr('data-href');
            fonction = 'getProduits';
        }

        $.post(
            url,
            $data,
            function(json){
                if(json.code === 1){
                    $("#medium-modal").modal("hide");
                    fonction === 'getTypes' ? getTypes($("#table-types").attr('data-href'),null) : getProduits($("#table-produits").attr('data-href'),null);
                    swal("Succès",json.msg, "success");
                }else if(json.code === 0){
                    $this.closest('div.modal').find('p.form-error').html(json.msg);
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    // End envoie


    /**
     * Get produits pour un fournisseur
     */
    function getProduits(href,$btn){
        $("#produits").empty();
        if($btn)
            $btn.button('loading');
        $.get(
            href,
            {},
            function(json){
                if(json.code === 1){
                    $("#produits").html(json.html);
                    $('#table-produits').DataTable({
                        responsive: true,
                        "processing": false,
                        "bFilter": false,
                        "serverSide": false,
                        "pageLength": 50,
                        "bLengthChange":false,
                        'info': false,
                        "order": [[ 1, "asc" ]]
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

    // Afficher la liste des produits pour un fournisseur

    $("body").on('click','.fournisseur-get-produits',function(){
        getProduits($(this).attr('data-href'),$(this));
    });
    // end liste

    //  Afficher formulaire pour ajouter un produit/matiere pour un fournisseur

    $("body").on('click','#add-produit-to-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {
                'fournisseur':$this.attr('data-fournisseur'),
            },
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.checkInput.initialise();
                    if($("#appbundle_produit_matiere").length)
                        $("#appbundle_produit_matiere").parsley();
                    if($("#appbundle_produit_fini").length)
                        $("#appbundle_produit_fini").parsley();
                    $("#small-modal").modal('show');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    // end formulaire

    //  hide/show attribut of product

    /*$("body").on('change','#appbundle_produit_isMatierePremiere',function(){
        if($(this).is(':checked'))
            $('div.produit').hide();
        else
            $('div.produit').show();

    });*/

    // end


    // envoyer le formulaire d'ajout des produits

    $("body").on('submit','#appbundle_produit_matiere,#appbundle_produit_fini',function(e){
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
                    getProduits($("#table-produits").attr('data-href'),null);
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

    // detacher une designation pour fourniseeur

    $("body").on('click','.produit-fournisseur-delete',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    getProduits($("#table-produits").attr('data-href'),null);
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

    // end detach

    // Supprimer un fournisseur

    $("body").on('click','.delete-fournisseur',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer ce fournisseur ?",
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
                            $("#table-fournisseurs-other").DataTable().ajax.reload();
                            $("#table-fournisseurs-MP").DataTable().ajax.reload();
                            $("#table-fournisseurs-PI").DataTable().ajax.reload();
                        }else if(json.code === 0)
                            swal('Erreur',json.msg,'error');
                        $this.button('reset');
                    },
                    'JSON'
                ).fail(function ($xhr) {
                    $this.button('reset');
                    swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                });
            });
    });

    //end suppression


});