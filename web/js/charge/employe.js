$(document).ready(function(){

    $('#table-charges-employes').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info": false,
        "createdRow": function ( row, data, index ) {
            if (!data.mode) {
                $(row).css({
                    'background-color': 'mistyrose'
                });
            }
        },
        "ajax": {
            "url": $(location).attr('href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                if($(".filter#id").val() !=='')
                    criteres['id']=$(".filter#id").val();
                else{
                    $(".filter").each(function(){
                        if($(this).val() !== '')
                            criteres[$(this).attr('id')]=$(this).val();
                    });
                }
                d.criteres = criteres;
            },
            "dataSrc" : function (json) {
                $("div#totals").remove();
                $("#table-charges-employes_wrapper").children('div.row:eq(1)').after(json.totals);
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "date"},
            {"data": "type"},
            {"data": "montant"},
            {"data": "mode"},
            {"data": "cheque"},
            {"data": "dateCheque"},
            {"data": "employe"},
            {"data": "observation"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3,10]},
            {className: "text-center", "targets": [4]},
            {className: "bg-ligne", "targets": 0}
        ],
        "order": [[ 2, "desc" ]],
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
        $("#table-charges-employes").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-charges-employes").DataTable().page.len($("#length").val()).draw();
    });


    $("#add-charge-employe").on('click',function(e){
        e.preventDefault();
       $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#nouveau-tab").find('div.x_content').html(json.form);
                    $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.checkInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_charge").parsley();
                    if($("div.line-charge-autre").length > 0)
                        makeOtherFormRequired();
                    $("#liste-tab").removeClass('active in');
                    $("#nouveau-tab").addClass('active in');
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('change','.check-one',function(){
        var statu = $(this).is(':checked');
        $("#check-all").prop('checked',allChecked());
        makeRequired();
    });
    $('body').on('change','select.mode',function(){
        $this = $(this);
        if ($this.find('option:selected').text() === 'Mode Paiement') {
            if ($this.closest('.line-charge').find('input:hidden.isPaid').length)
                $this.closest('.line-charge').find('input:hidden.isPaid').remove();
        } else
            $this.closest('.line-charge').append('<input class="isPaid" value="1" type="hidden" name="appbundle_charge['+$this.closest('.line-charge').attr('data-index')+'][isPaid]" id="appbundle_charge_isPaid_'+$this.closest('.line-charge').attr('data-index')+'" />');
        makeRequired();
    });
    function makeRequired(){
        $('table#charge-employes tbody tr').each(function(){
            if($(this).find('.check-one').is(':checked')){
                $(this).find('select.required,input.required').attr('required','required');
            }else{
                $(this).find('select.required,input.required').removeAttr('required');
            }
        });
        $("#appbundle_charge").parsley().destroy();
        $("#appbundle_charge").parsley();
        $("#appbundle_charge").parsley().validate();
    }

    $("body").on('click','.bind_img',function(e){
        e.preventDefault();
        $(this).next('input:file').trigger('click');
    });

    $("body").on('change','input:file.cheque,input:file.facture',function(){
        $btn = $(this).prev('button.bind_img');
        if(this.files.length>0){
            $btn.html('<img class="img-responsive" style="width: 80px;" src="'+URL.createObjectURL(this.files[0])+'" />');
            $btn.css("padding","0");
        }else{
           $btn.html('Choisir photo');
            $btn.css("padding","");
        }
    })

     $("body").on('change','#check-all',function(){
        var statu = $(this).is(':checked');
       $('.check-one').prop('checked',statu);
        makeRequired();
    });
    function allChecked(){
        var allChecked = true;
        $('table#charge-employes tbody tr').each(function(){
            if(!$(this).find('.check-one').is(':checked'))
                allChecked = false;
        });
        return allChecked;
    }
    $("body").on('click','.delete-charge',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer cette charge ?",
                text: "Si vous supprimez cette charge vous ne pouvez pas la récupérer",
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
                            $("#table-charges-employes").DataTable().ajax.reload();
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
    $("body").on('submit','#appbundle_charge',function(e){
        e.preventDefault();
        $this = $(this);
        var donnes = new FormData();
            $this.find('tr.line-charge').each(function() {
                if($(this).find('.check-one').is(':checked')){
                    $(this).find('select,input[name],textarea').each(function(){
                        if ($(this).is('select[multiple]')){
                            $select = $(this);
                            $(this).find('option:selected').each(function(index){
                                donnes.append($select.attr('name')+'[]', $(this).val());
                            });
                        }else if ($(this).is('input:file') && $(this)[0].files.length > 0)
                            donnes.append($(this).attr('name'), $(this)[0].files[0]);
                        else if (!$(this).is('input:file') && !$(this).is('input:checkbox'))
                            donnes.append($(this).attr('name'), $(this).val());
                    });
                }
            });
        $btn = $this.find('button[type=submit]');
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
                    $("#liste-charges").tab("show");
                    swal({
                        title:"Succès",
                        text:json.msg,
                        type:"success",
                        html: true
                    });
                    $("#table-charges-employes").DataTable().ajax.reload();
                    $("#nouveau-tab").removeClass('active in');
                    $("#liste-tab").addClass('active in');
                }else if(json.code === 0){
                    $("#form-error").html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });

    $("#add-charge-employe").on('hidden.bs.tab',function(){
        $("#nouveau-tab").find('div.x_content').empty();
    });


    $("body").on('click','.edit-charge',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $this.button('reset');
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.photobox.initialise();
                    $.Admin.checkInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#large-modal").modal('show');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','div#large-modal #submit',function(){
        if($("#appbundle_charge_edit").length)
            $("#appbundle_charge_edit").trigger('submit');
    });

    $("body").on('submit','#appbundle_charge_edit',function (e){
       e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        var donnes = new FormData();
        $this.find('input,select,textarea').each(function() {
            if ($(this).is('select[multiple]')){
                $select = $(this);
                $(this).find('option:selected').each(function(index){
                    donnes.append($select.attr('name')+'[]', $(this).val());
                });
            }else if ($(this).is('input:file') && $(this)[0].files.length > 0)
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
                    $("#table-charges").DataTable().ajax.reload();
                    $("#nouveau-tab").removeClass('active in');
                    $("#liste-tab").addClass('active in');
                }else if(json.code === 0){
                    $("#form-error").html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });

    $("body").on('click','.bind_img_edit',function(e){
        e.preventDefault();
        $(this).closest('.form-group').find('input:file').trigger('click');
    });

    $("body").on('change','#appbundle_charge_edit input:file',function(){
       if (this.files.length > 0)
           $(this).closest('.form-group').find('a.pic-show').attr('href',URL.createObjectURL(this.files[0]));
       else
           $(this).closest('.form-group').find('a.pic-show').attr('href',$(this).closest('.form-group').find('a.pic-show').attr('data-href'));
    $.Admin.photobox.initialise();
    });
    $('#table-charges-employes').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
    } );

    $("body").on('click','.liste-charges',function(e){
       e.preventDefault();
        $("#nouveau-tab").removeClass('active in');
        $("#liste-tab").addClass('active in');
    });

    $("body").on('blur keyup','.ht-edit,.tva-edit',function(){
        $form = $(this).closest('form');
        if($form.find('input.tva-edit').val() !== '' && $form.find('input.ht-edit').val() !== ''){
            var tva = parseInt($form.find('input.ht-edit').val()) * parseInt($form.find('input.tva-edit').val()) / 100;
            var ttc = parseInt($form.find('input.ht-edit').val()) + tva;
            $form.find('.ttc-edit').val(ttc.toFixed(2));
        }else
            $form.find('.ttc-edit').val('');
    });

    autoRun();
    function autoRun(){
        if($("input:hidden#selectedType").length){
            console.log('autorun');
            $this = $("#add-charge-employe");
            $.get(
                $this.attr('data-href'),
                {'type':$("input:hidden#selectedType").val()},
                function(json){
                    if(json.code === 1){
                        $("#nouveau-tab").find('div.x_content').html(json.form);
                        $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                        $.Admin.select.activate();
                        $.Admin.controle.saisie();
                        $.Admin.dateInput.initialise();
                        $.Admin.checkInput.initialise();
                        autosize($('textarea.auto-growth'));
                        $("#appbundle_charge").parsley();
                        if($("div.line-charge-autre").length > 0)
                            makeOtherFormRequired();
                        $("#liste-tab").removeClass('active in');
                        $("#nouveau-tab").addClass('active in');
                    }
                },
                "JSON"
            ).fail(function ($xhr) {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
            });
        }
    }

});