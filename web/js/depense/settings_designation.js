$(document).ready(function(){
    $("#menu-to-block").css('display','block');

    // liste datatable

    $("#table-types").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-types").attr('data-href'),
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
            {"data": "fournisseurs"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3,4]},
            {className: "bg-ligne", "targets": 0},
            {className: "text-center", "targets": 4}
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
        $("#table-types").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-types").DataTable().page.len($("#length").val()).draw();
    });



    // End filtre
    // afficher formulaire

    // Ouvrir le formulaire d'ajout et modification

    $("body").on('click','#add-type,.edit-type,.edit-sous-type',function(){
        $this = $(this);
        $this.button('loading');
        if($this.hasClass('edit-sous-type'))
            $data = {'settings':'fils'};
        else  if($this.hasClass('edit-type'))
            $data = {'settings':'parent','operation':'edit'};
        else
            $data = {'settings':'parent'};
        $.get(
            $this.attr('data-href'),
            $data,
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
                    if($("#table-sous-types").length)
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


    // Archiver/Desarchiver un produit

    $("body").on('click','.type-toggle-state,.sous-type-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
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

    $("body").on('click','.delete-type,.sous-type-delete',function(){
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
                            if($this.hasClass('sous-type-delete'))
                                getSousTypes($("#table-sous-types").attr('data-href'),null);
                            $("#table-types").DataTable().ajax.reload();
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

    /**
     * Get sous-types pour un type
     */
    function getSousTypes(href,$btn){
        $("#sous-types").empty();
        if($btn)
            $btn.button('loading');
        $.get(
            href,
            {'settings':'sous-types'},
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

    $("body").on('click','.get-sous-type',function(){
      getSousTypes($(this).attr('data-href'),$(this));
    });

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

    window.Parsley.on('form:error', function() {
        if(this.$element.selector === '#appbundle_typedepense'){
            $(this.$element.selector).find('ul.parsley-errors-list').css('position','absolute');
        }
    });


    // Afficher la liste des types

    $('body').on('click','.atache-detache-fournisseurs-to-type',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.fournisseurs);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $("#ms-select-fournisseurs").css('margin','auto');
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
        $.post(
            $('#select-fournisseurs').attr('data-href'),
            {'fournisseurs':$('#select-fournisseurs').val()},
            function(json){
                if(json.code === 1){
                    $("#medium-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    $("#table-types").DataTable().ajax.reload();
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

    // Ouvrir le formulaire d'ajout et modification

    $("body").on('click','#add-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {
                'typeVente':'0'
            },
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_fournisseur").parsley();
                    $("#medium-modal").modal('hide');
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
        if($("#appbundle_fournisseur").length)
            $("#appbundle_fournisseur").trigger('submit');
    });


    // End formulaire


    // Envoyer un formulaire

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
                    $("#medium-modal").modal('show');
                    var option = new Option($.parseJSON(json.fournisseur).nom,$.parseJSON(json.fournisseur).id,true,true);
                    $("#select-fournisseurs").append(option);
                    $.Admin.select.refresh();
                    $("#ms-select-fournisseurs").css('margin','auto');
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
});