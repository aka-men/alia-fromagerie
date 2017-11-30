$(document).ready(function(){
    var index = 0;
    var indexSubForm = 0;
    $('#table-achats-PF').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info":false,
        "createdRow": function ( row, data, index ) {
            if (!data.mode) {
                $(row).css({
                    'background-color': 'mistyrose'
                });
            }
        },
        "ajax": {
            "url": $('#table-achats-PF').attr('data-href'),
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
                $("#table-achats-PF_wrapper div#totals").remove();
                $("#table-achats-PF_wrapper").children('div.row:eq(1)').after(json.totals);
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "date"},
            {"data": "facture"},
            {"data": "fournisseur"},
            {"data": "montant"},
            {"data": "tva"},
            {"data": "mode"},
            {"data": "cheque"},
            {"data": "dateCheque"},
            {"data": "observation"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,11]},
            {className: "text-center", "targets": 3},
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
    $('#table-achats-MP').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info":false,
        "createdRow": function ( row, data, index ) {
            if (!data.mode) {
                $(row).css({
                    'background-color': 'mistyrose'
                });
            }
        },
        "ajax": {
            "url": $('#table-achats-MP').attr('data-href'),
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
                $("#table-achats-MP_wrapper div#totals").remove();
                $("#table-achats-MP_wrapper").children('div.row:eq(1)').after(json.totals);
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "date"},
            {"data": "facture"},
            {"data": "fournisseur"},
            {"data": "montant"},
            {"data": "tva"},
            {"data": "mode"},
            {"data": "cheque"},
            {"data": "dateCheque"},
            {"data": "observation"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,11]},
            {className: "text-center", "targets": 3},
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
        $("#table-achats-MP").DataTable().ajax.reload();
        $("#table-achats-PF").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $("#table-achats-MP").DataTable().page.len($("#length").val()).draw();
        $("#table-achats-PF").DataTable().page.len($("#length").val()).draw();
    });

    $("body").on('click','#add-achat',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {"type":$this.attr('data-type')},
            function(json){
                if(json.code === 1){
                    $this.button('reset');
                    $("#nouveau-tab").find('div.x_content').html(json.form);
                    $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.checkInput.initialise();
                    $.Admin.photobox.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_achat").parsley();
                    $("#appbundle_achat_modePayment").trigger('change');
                    $("#liste-tab").removeClass('active in');
                    $("#nouveau-tab").addClass('active in');
                    index = 0;
                    indexSubForm = 0;
                    $("#appbundle_achat_fournisseur").parsley().on('field:error', function() {
                        $("#appbundle_achat_fournisseur").closest('div.form-group').find('button[data-id=appbundle_achat_fournisseur]').css({
                            'background': '#FAEDEC',
                            'border':'1px solid #E85445'
                        });
                    });
                    $("#appbundle_achat_fournisseur").parsley().on('field:success', function() {
                        $("#appbundle_achat_fournisseur").closest('div.form-group').find('button[data-id=appbundle_achat_fournisseur]').css({
                            'background': '',
                            'border':''
                        });
                    });
                    $("#add-line").trigger('click');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $('body').on('change','#appbundle_achat_modePayment',function() {
        $this = $(this);
        if ($this.find('option:selected').text() === 'Mode paiement') {
            if ($this.closest('form').find('input:hidden#appbundle_achat_isPaid').length)
                $this.closest('form').find('input:hidden#appbundle_achat_isPaid').remove();
        } else
            $this.closest('form').append('<input type="hidden" value="1" id="appbundle_achat_isPaid" name="appbundle_achat[isPaid]" />');
        if($this.find('option:selected').text() === 'Espèce' || $this.find('option:selected').text() === 'Mode paiement' ){
            $('div.cheque').hide();
            $('input.cheque-info').val('');
        }else{
            $('div.cheque').show();
        }
        $("#appbundle_achat").parsley().destroy();
        $("#appbundle_achat").parsley();
    });

    $("body").on('click','.delete-achat',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer cette achat ?",
                text: "Si vous supprimez cette achat vous ne pouvez pas la récupérer",
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
                            $("#table-achats-MP").DataTable().ajax.reload();
                            $("#table-achats-PF").DataTable().ajax.reload();
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

    $("body").on('click','#add-line',function(e){
        e.preventDefault();
        $this = $(this);
        var template = $this.attr('data-prototype').replace(/__name__/g,index);
        $("#details").children('div.row.action').before(template);
        index++;
        $.Admin.select.activate();
        $.Admin.controle.saisie();
        $("#appbundle_achat").parsley();
        makeLineUnDeleted();
        getProduits($("#appbundle_achat_produits_"+(index - 1)+"_produit"));
    });

    $("body").on('change','select.produit',function(){
        $this = $(this);
        $line = $this.closest('div.line');
        if($this.val()){
            $line.find('input.prix').val($this.find('option:selected').attr('data-prix'));
            $line.find('input.qte').val('1');
            if ($this.find('option:selected').attr('data-isMatierePremiere') === 'true') {
                $.post(
                    $this.attr('data-href-sub-form'),
                    {
                        'id':$this.val(),
                        'index':indexSubForm
                    },
                    function(json){
                        if(json.code === 1){
                            $this.closest('div.row.line').find('div.production').html(json.formProduction);
                            $this.closest('div.row.line').find('div.analyses').html(json.formOptions);
                            indexSubForm = indexSubForm + 15;
                            $("#appbundle_achat").parsley().destroy();
                            $("#appbundle_achat").parsley();
                        }
                    },
                    'JSON'
                ).fail(function ($xhr) {
                    $this.closest('div.row.line').find('div.analyses,div.production').empty();
                    swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                });
            }else{
                $this.closest('div.row.line').find('div.analyses,div.production').empty();
            }
        }else{
            $line.find('input.prix').val('');
            $line.find('input.qte').val('');
            $line.find('input.sousTotal').val('');
            $this.closest('div.row.line').find('div.analyses,div.production').empty();
        }
        $line.find('input.qte').trigger('change');
    });

    $('body').on('change','#appbundle_achat_fournisseur',function(){
        if($(this).attr('data-trigger') && $(this).attr('data-trigger') === 'true')
            getProduits(null);
    });

    function getProduits($element) {
        url = $("#appbundle_achat_fournisseur").attr('data-produits-href');
        if ($element){
            $element.empty();
        }
        else{
            $("select.produit").each(function(){
                $(this).empty();
            });
        }
        $data = {};
        if($("#appbundle_achat_fournisseur").find('option:selected').text() !== 'Fournisseur')
            $data ['fournisseur'] = $("#appbundle_achat_fournisseur").val();
        var except = [];
        $("select.produit").each(function(){
            if($(this).val())
                except.push($(this).val());
        });
        if(except.length > 0)
            $data['except'] = except;
        $.post(
            url,
            $data,
            function(json){
                var options = '<option value selected>Produit</option>';
                $.each(json.items,function(index,item){
                    var option = '<option data-isMatierePremiere="'+item.isMatierePremiere+'" data-prix="'+item.prixAchat+'" value="'+item.id+'">'+item.titre+'</option>';
                    options += option;
                });
                if($element) {
                    $element.html(options);
                    $.Admin.select.refresh();
                    $element.trigger('change');
                }
                else{
                    $("select.produit").each(function(){
                        $(this).html(options);
                        $(this).trigger('change');
                    });
                    $.Admin.select.refresh();
                }
            },
            'JSON'
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    }

    $("body").on('click','#delete-line',function(e){
        e.preventDefault();
        $this = $(this);
        $this.closest('div.line').remove();
        calcul();
        makeLineUnDeleted();
    });

    $("body").on('keyup blur change','input.qte,input.prix',function(){
        $this = $(this);
        $inputQte = $this.closest('div.line').find('input.qte');
        $inputPrix = $this.closest('div.line').find('input.prix');
        $inputSousTotal = $this.closest('div.line').find('input.sousTotal');
        var qte = isNaN(parseInt($inputQte.val())) ? 0 : parseInt($inputQte.val());
        var prix = isNaN(parseFloat($inputPrix.val())) ? 0 : parseFloat($inputPrix.val());
        var sousTotal = prix * qte;
        $inputSousTotal.val(sousTotal.toFixed(2));
        calcul();
    });

    $("body").on('keyup blur','#appbundle_achat_tva',function(){
        calcul();
    });


    function calcul(){
        var ht = 0;
        $("input.sousTotal").each(function(){
            var st = isNaN(parseFloat($(this).val())) ? 0 : parseFloat($(this).val());
            ht+= st;
        });
        $("#appbundle_achat_montantHt").val(ht.toFixed(2));
        var tva = isNaN(parseFloat($("#appbundle_achat_tva").val())) ? 0 : parseFloat($("#appbundle_achat_tva").val());
        var taxes = ht*tva/100;
        $("#taxes").text(taxes.toFixed(2) + 'DH');
        var ttc = parseFloat(ht) + parseFloat(taxes);
        $("#appbundle_achat_montantTtc").val(ttc.toFixed(2));
    }

    window.Parsley.on('form:error', function() {
        if(this.$element.selector === '#appbundle_achat'){
            $(this.$element.selector).find('ul.parsley-errors-list').css('display','none');
        }
    });


    $("body").on('submit','#appbundle_achat',function(e){
        e.preventDefault();
        $this = $(this);
        var donnes = new FormData();
        $this.find('select[name],input[name],textarea[name]').each(function(){
            if ($(this).is('input:file') && $(this)[0].files.length > 0)
                donnes.append($(this).attr('name'), $(this)[0].files[0]);
            else if (!$(this).is('input:file') && $(this).is('input:checkbox') && $(this).is(':checked'))
                donnes.append($(this).attr('name'), 1);
            else if (!$(this).is('input:file') && !$(this).is('input:checkbox'))
                donnes.append($(this).attr('name'), $(this).val());
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
                    $("#nouveau-tab").removeClass('active in');
                    $("#liste-tab").addClass('active in');
                    swal("Succès",json.msg, "success");
                    $("#table-achats-MP").DataTable().ajax.reload();
                    $("#table-achats-PF").DataTable().ajax.reload();
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

    $("#add-achat").on('hidden.bs.tab',function(){
        $("#nouveau-tab").find('div.x_content').empty();
    });


    $("body").on('click','.edit-achat',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    $("#nouveau-tab").find('div.x_content').html(json.form);
                    $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.checkInput.initialise();
                    $.Admin.photobox.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_achat").parsley();
                    $("#appbundle_achat_modePayment").trigger('change');
                    $("#liste-tab").removeClass('active in');
                    $("#nouveau-tab").addClass('active in');
                    index = $("div.line").length + 1;
                    nbrOption = $("div.option").length;
                    nbrProduction = $("div.production").length;
                    indexSubForm = (nbrOption > nbrProduction) ? nbrOption : nbrProduction;
                    makeLineUnDeleted();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.bind_img',function(e){
        e.preventDefault();
        $(this).closest('.form-group').find('input:file').trigger('click');
    });

    $("body").on('change','#appbundle_achat input:file',function(){
        if (this.files.length > 0)
            $(this).closest('.form-group').find('a.pic-show').attr('href',URL.createObjectURL(this.files[0]));
        else
            $(this).closest('.form-group').find('a.pic-show').removeAttr('href');
        $.Admin.photobox.initialise();
    });

    $('#table-achats-MP,#table-achats-PF').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.btnTable.style();
    } );

    $("body").on('click','.show-tab-liste',function(e){
        e.preventDefault();
        $("#nouveau-tab").removeClass('active in');
        $("#liste-tab").addClass('active in');
    });

    function makeLineUnDeleted(){
        if($('div.line').length === 1)
            $('div.line').find('button#delete-line').prop('disabled',true);
        else
            $('div.line').find('button#delete-line').prop('disabled',false);
    }


    // Gestion fournisseurs

    $("body").on('click','.add-fournisseur',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {'typeVente':$this.attr('data-type')},
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
                    var fournisseur = $.parseJSON(json.fournisseur);
                    var option = new Option(fournisseur.nom,fournisseur.id,true,true);
                    $("#appbundle_achat_fournisseur").append(option);
                    $.Admin.select.refresh();
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

    // End fournisseurs

    // Details achat

    $("body").on('click','.details-achat',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.html);
                    $("#large-modal").find('.modal-title').html($this.attr('data-title'));
                    $("#large-modal").find('div.modal-footer').hide();
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

    // end details

    // production

    $("body").on('click','#get-production',function(){
        $this = $(this);
        $this.button('loading');
        $data ={};
        if($("#annee").val() && $("#annee").val() !== '')
            $data['annee'] = $("#annee").val();
        if($("#mois").val() && $("#mois").val() !== '')
            $data['mois'] = $("#mois").val();
        $.post(
            $this.attr('data-href'),
            $data,
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.html);
                    $("#large-modal").find('.modal-title').html("Tableau de production");
                    $("#large-modal").find('div.modal-footer').hide();
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

    // end production

});