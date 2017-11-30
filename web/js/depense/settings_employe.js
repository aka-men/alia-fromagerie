$(document).ready(function(){

    $("#menu-to-block").css('display','block');

    var $container = null;
    var index = 0;

    $('body').on('click','#add-document',function(e) {
        e.preventDefault();
        addDocument($container);
    });

    function addDocument($container) {
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, 'Document n°' + (index+1))
            .replace(/__name__/g,        index)
        ;
        var $prototype = $(template);
        $container.append($prototype);
        index++;
    }

    $('body').on('click','.delete-document',function(e){
        e.preventDefault();
        $(this).closest('div.doc-col').remove();
    });

    $('body').on('click','.bind_img',function(e){
        e.preventDefault();
        $(this).next('input[type=file]').trigger('click');
    });

    $('body').on('change','.image-file',function(){
        if(this.files.length > 0){
            var image = this.files[0];
            $(this).closest('div.doc-col').find('img.img-responsive').attr('src',URL.createObjectURL(image));
        }else{
            $(this).closest('div.doc-col').find('img.img-responsive').removeAttr('src');
            $(this).attr('required','required');
        }
    });


    var table = $("#table-employes").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info": false,
        "ajax": {
            "url": $("#table-employes").attr('data-href'),
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
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "nom"},
            {"data": "cin"},
            {"data": "email"},
            {"data": "gsm"},
            {"data": "documents"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [6,7]},
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

    $(".filter").on('change keyup',function(){
        $("#table-employes").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-employes").DataTable().page.len($("#length").val()).draw();
    });

    $("body").on('click','#add-employe,.edit-employe',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.tagsInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_employe").parsley();
                    $("#large-modal").modal('show');
                    $container = $('div#appbundle_employe_imagesDocuments');
                    index = $container.find('button').length;
                    $('div.galery').photobox('a.photo-box',{ time:0 });
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
        if($("#appbundle_employe").length)
            $("#appbundle_employe").trigger('submit');
    });

    $("body").on('submit','#appbundle_employe',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        var donnes = new FormData();
        $this.find('select,input,textarea').each(function(){
            if ($(this).is('input:file') && $(this)[0].files.length > 0)
                donnes.append($(this).attr('name'), $(this)[0].files[0]);
            else if (!$(this).is('input:file') && $(this).is('input:checkbox') && $(this).is(':checked'))
                donnes.append($(this).attr('name'), 1);
            else if (!$(this).is('input:file') && !$(this).is('input:checkbox'))
                donnes.append($(this).attr('name'), $(this).val());
        });
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
                    $("#large-modal").modal("hide");
                    swal("Succès",json.msg, "success");
                    $("#table-employes").DataTable().ajax.reload();
                }else if(json.code === 0){
                    $this.closest('div.modal').find('p.form-error').html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });

    $("body").on('click','.employe-toggle-state',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    $("#table-employes").DataTable().ajax.reload();
                }
                $this.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-employe',function(){
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
                            $("#table-employes").DataTable().ajax.reload();
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

    $("body").on('click','.remove-image',function(){
        var id = $(this).attr('data-id');
        var nbr = $("#appbundle_employe").find('input:hidden.deleted-image').length;
        $("#appbundle_employe").append('<input type="hidden" class="deleted-image" name="deleted_image['+nbr+']" value="'+id+'" />');
    });

    table.on( 'processing.dt', function ( e, datatable, row, showHide, update ) {
        $('.photo-box').closest('td').photobox('[data-fancybox]',{ time:0 });
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );

    $('body').on('DOMNodeInserted','tr.child',function(){
        $('.photo-box').parent('.dtr-data').photobox('[data-fancybox]',{ time:0 });
        $.Admin.bootstrap.toolTip();
    });
    $('#table-employes').on( 'draw.dt', function () {
        $.Admin.bootstrap.toolTip();
    } );

});