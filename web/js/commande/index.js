/**
 * Created by abdelhak on 29/05/2017.
 */
$(document).ready(function(){

    var $clientOptions = '';
    var $commercialeOptions = '';
    var $entrepriseOptions = '';
    var $firstOption = '';
    var nbrFrais = 0;
    var nbrPaiements = 0;
    var nbrProduits;

    $('#table-commandes tbody').on( 'click', 'td.select', function () {
        $(this).closest('tr').toggleClass('selected');
        if( $('#table-commandes tbody').find('tr.selected').length > 0)
            $("#collection-action").show();
        else
            $("#collection-action").hide();
        if(allOrderChecked() === true)
            $("#select-all-order").prop("checked",true);
        else
            $("#select-all-order").prop("checked",false);
    });

    function allOrderChecked() {
        var allChecked = true;
        $('#table-commandes tbody tr').each(function(index){
           if(!$(this).hasClass('selected'))
               allChecked = false;
        });
        return allChecked;
    }

    $("body").on('change',"#select-all-order",function(){
        if($(this).is(":checked"))
            $('#table-commandes tbody tr').addClass('selected');
        else
            $('#table-commandes tbody tr').removeClass('selected');
        if( $('#table-commandes tbody').find('tr.selected').length > 0)
            $("#collection-action").show();
        else
            $("#collection-action").hide();
    });

    $('#table-commandes').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info":false,
        "createdRow": function ( row, data, index ) {
            if (parseFloat(data.reste) > 0) {
                $(row).find('td:eq(11)').css({
                    'color': 'red'
                });
            }
            if(parseFloat(data.avanceNum) > 0){
                $(row).find('td:eq(10)').css({
                    'color': 'green'
                });
            }
        },
        "ajax": {
            "url": $('#table-commandes').attr('data-href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                if($(".filter#id").val() !=='')
                    criteres['id']=$(".filter#id").val();
                else{
                    $(".filter").each(function(){
                        if($(this).attr('id') === 'client-entreprise' && $(this).val() && $(this).val() !== ''){
                            criteres[$(this).find('option:selected').attr('data-type')]=$(this).val();
                        }
                        else if($(this).is("input:checkbox") && $(this).is(":checked"))
                            criteres[$(this).attr('id')]=$(this).val();
                        else if(!$(this).is("input:checkbox") && $(this).val() !== '')
                            criteres[$(this).attr('id')]=$(this).val();
                    });
                }
                d.criteres = criteres;
            },
            "dataSrc" : function (json) {
                $("#select-all-order").prop("checked",false);
                $("div#totals").remove();
                $("div#produits").remove();
                $("#table-commandes_wrapper").children('div.row:eq(1)').after(json.totals);
                $("div#totals").after(json.produits);
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "date"},
            {"data": "client"},
            {"data": "ht"},
            {"data": "tva"},
            {"data": "ttc"},
            {"data": "facture"},
            {"data": "frais"},
            {"data": "total"},
            {"data": "avance"},
            {"data": "reste"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3,8,12,9,10,11,7]},
            {className: "text-center", "targets": [4,5,6,7,8,9,11,10]},
            {className: "bg-ligne select pointer", "targets": 0}
        ],
        "order": [[ 1, "desc" ]],
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
        $this = $(this);
        if($this.attr('id') !== 'mois' && $this.attr('id') !== 'annee' && $this.val() !== ''){
            $('.filter#mois').selectpicker('val','');
            $('.filter#annee').selectpicker('val','');
        }
        $("#table-commandes").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $('#table-commandes').DataTable().page.len($("#length").val()).draw();
    });

    $("#add-commande").on('hidden.bs.tab',function(){
        $("#nouveau-tab").find('div.x_content').empty();
    });

    $("body").on('click','#cancel',function(e){
        e.preventDefault();
        $("#nouveau-tab").removeClass('active in');
        $("#liste-tab").addClass('active in');
    });

    $("#add-commande").on('click',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
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
                    $("#commande_step_1").parsley();
                    $("#liste-tab").removeClass('active in');
                    $("#nouveau-tab").addClass('active in');
                    $clientOptions = $("#commande_client").find('optgroup#clients');
                    $commercialeOptions = $("#commande_client").find('optgroup#commerciaux');
                    $entrepriseOptions = $("#commande_client").find('optgroup#entreprises');
                    $firstOption = $("#commande_client").find("option:first");
                    $(".radio-typeProduit").trigger('change');
                    $(".radio-typeClient").trigger('change');
                    $("#commande_client").parsley().on('field:error', function() {
                        $("#commande_client").closest('div.bootstrap-select').find('ul.parsley-errors-list').css('display','none');
                        $("#commande_client").closest('div.form-group').find('button[data-id=commande_client]').css({
                            'background': '#FAEDEC',
                            'border':'1px solid #E85445'
                        });
                    });
                    $("#commande_client").parsley().on('field:success', function() {
                        $("#commande_client").closest('div.form-group').find('button[data-id=commande_client]').css({
                            'background': '',
                            'border':''
                        });
                    });
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    // gestion clients


    $("body").on('click','#add-client,#add-commerciale',function(){
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
        if($("#form_statut").length)
            $("#form_statut").trigger('submit');
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
                    var client = $.parseJSON(json.client);
                    var option = new Option(client.nom+' '+client.prenom+' '+client.sur_nom,client.id,true,true);
                    $(option).attr('data-object','client');
                    if(client.is_commercial)
                        $commercialeOptions.append(option);
                    else
                        $clientOptions.append(option);
                    $(".radio-typeClient").trigger('change');
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

    // end clients

    $("body").on('change','.radio-typeProduit',function(){
        var valeur = $("input[name=typeProduit]:checked","#commande_step_1").val();
        if(valeur === 'all')
            $("#produits-alia,#produits-importation").show();
        else if (valeur === 'others'){
            $("#produits-importation").show();
            $("#produits-alia").hide();
            $("#produits-alia").find('input:checkbox').each(function(){
                $(this).prop('checked',false);
            });
        }else if (valeur === 'alia'){
            $("#produits-alia").show();
            $("#produits-importation").hide();
            $("#produits-importation").find('input:checkbox').each(function(){
                $(this).prop('checked',false);
            });
        }

    });

    $("body").on('change','.radio-typeClient',function(){
        var valeur = $("input[name=typeClient]:checked","#commande_step_1").val();
        if(valeur === 'all')
            $("#commande_client").empty().append($firstOption).append($clientOptions).append($commercialeOptions).append($entrepriseOptions);
        else if (valeur === 'clients'){
            $("#commande_client").empty().append($firstOption).append($clientOptions);
        }else if (valeur === 'commerciaux'){
            $("#commande_client").empty().append($firstOption).append($commercialeOptions);
        }else if (valeur === 'entreprises'){
            $("#commande_client").empty().append($firstOption).append($entrepriseOptions);
        }
        $firstOption.prop('selected',true);
        $.Admin.select.refresh();
    });


    // Ajouter une nouvelle entreprise

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
        if($("#form_produits").length)
            $("#form_produits").trigger('submit');
        if($("#form_echantillons").length)
            $("#form_echantillons").trigger('submit');
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
                    $(option).attr('data-object','entreprise');
                    $entrepriseOptions.append(option);
                    $(".radio-typeClient").trigger('change');
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

    $("body").on('change','#commande_client',function(){
        $("#used_type").val($(this).find("option:selected").attr('data-object'));
    });

    // End entreprise

    $("body").on('submit','#commande_step_1',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.find('button[type=submit]');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $("#nouveau-tab").find('div.x_content').html(json.form);
                    $.Admin.select.activate();
                    $.Admin.dateInput.initialise();
                    $.Admin.controle.saisie();
                    nbrFrais = 0;
                    nbrPaiements = 0;
                    nbrProduits = $('tr.details-line').length;
                    if($("tr.details-line").length <= 1)
                        $(".delete-line-produit").prop('disabled','disabled');
                    calcul();
                    $("select.mode").trigger('change');
                    $('#appbundle_commande_date').datepicker({
                        dateFormat: 'dd/mm/yy',
                        onSelect: function (dateText,elem) {
                            calcul();
                        },
                        showButtonPanel: true,
                        currentText: "Aujourd'hui",
                        closeText: 'Fermer',
                        changeMonth: true,
                        changeYear: true,
                        dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
                        monthNamesShort : [ "janv", "Févr", "Mars", "Avr", "Mai", "Juil", "Juil", "Aout", "Sept", "Oct", "Nov", "Déc" ],
                        monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Setembre", "Octobre", "Novembre", "Décembre" ]
                    });
                    $("#select_client_entreprise").trigger('change');

                }
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('change blur keyup','input.qte,input.tva,input.prix,input.prix-frais,input.libelle,#appbundle_commande_tva,#appbundle_commande_remise',function () {
       calcul();
    });

    /**
     * Calculer les sous total et retourner le total des produits
     * @returns {number}
     */
    function calculSousTotals() {
        var sousTotals = 0;
        $("div#bon-details table>tbody").empty();
        $('tr.details-line').each(function(){
            var titre = $(this).find('td:first').find('span').html();
            $inputPrix = $(this).find('input.prix');
            $inputQte = $(this).find('input.qte');
            $inputSousTotal = $(this).find('input.st');
            var prix = isNaN(parseFloat($inputPrix.val())) ? 0 : parseFloat($inputPrix.val());
            var qte = isNaN(parseFloat($inputQte.val())) ? 0 : parseFloat($inputQte.val());
            var st = prix * qte;
            sousTotals += st;
            $inputSousTotal.val(st.toFixed(2));
           if($(this).hasClass('echantillon'))
                $("div#bon-details  table>tbody").append($("div#bon-details").attr('data-prototype-echantillon').replace(/__titre__/g,titre).replace(/__quantite__/g,qte));
            else
                $("div#bon-details  table>tbody").append($("div#bon-details").attr('data-prototype').replace(/__titre__/g,titre).replace(/__prixUnitaire__/g,prix.toFixed(2)).replace(/__quantite__/g,qte).replace(/__total__/g,st.toFixed(2)));
        });
        return sousTotals;
    }
    function calcul() {
        $("#date-span").html($("#appbundle_commande_date").val());
        $("div#frais-supp table>tbody").empty();
        var ht = calculSousTotals();
        var fraisTotal = 0;
        $("div.frais-line").each(function(){
            $inputFrais = $(this).find('input.prix-frais');
            $inputLabel = $(this).find('input.libelle');
            $inputTva = $(this).find('input.tva');
            var tva = isNaN(parseFloat($inputTva.val())) ? 0 : parseFloat($inputTva.val());
            var frais = isNaN(parseFloat($inputFrais.val())) ? 0 : parseFloat($inputFrais.val());
            var fraisTtc = frais + (frais * tva / 100);
            var label = $inputLabel.val();
            fraisTotal += fraisTtc;
            $("div#frais-supp table>tbody").append($("div#frais-supp").attr('data-prototype').replace(/__label__/g,label).replace(/__frais__/g,fraisTtc.toFixed(2)));
        });

        var remise = isNaN(parseFloat($("#appbundle_commande_remise").val())) ? 0 : parseFloat($("#appbundle_commande_remise").val());
        var reduction = ht*remise/100;
        if($("#appbundle_commande_remise").val() === ''){
            $("div#reduction").hide();
            $("#remise").empty();
            $("#span-reduction").empty();
        }else{
            $("div#reduction").show();
            $("#remise").html(remise);
            $("#span-reduction").html('-'+number_format(reduction,2,'.',' '));
        }

        var tva = isNaN(parseFloat($("#appbundle_commande_tva").val())) ? 0 : parseFloat($("#appbundle_commande_tva").val());
        var taxes = (ht - reduction)*tva/100;
        if($("#appbundle_commande_tva").val() === ''){
            $("div#taxes").hide();
            $("div#ttc").hide();
            $("#tva").empty();
            $("#span-taxes").empty();
        }else{
            $("div#taxes").show();
            $("div#ttc").show();
            $("#tva").html(tva);
            $("#span-taxes").html(number_format(taxes,2,'.',' '));
        }
        var ttc = (ht - reduction) + taxes;
        var total = ttc + fraisTotal;
        $("#span-ht").html(number_format(ht,2,'.',' '));
        $("#span-ttc").html(number_format(ttc,2,'.',' '));
        $("#span-total").html(number_format(total,2,'.',' ')+' DH');
        $("#appbundle_commande_montantHt").val(ht.toFixed(2));
        $("#appbundle_commande_montantTtc").val(ttc.toFixed(2));

        var avance = 0;
        $("div.paiement-line").each(function(){
            $inputMontant = $(this).find('input.prix');
            var montant = isNaN(parseFloat($inputMontant.val())) ? 0 : parseFloat($inputMontant.val());
            avance += montant;
        });
        $("#span-avance").html(number_format(avance,2,'.',' ')+' DH');

        var reste = total - avance;
        $("#span-reste").html(number_format(reste,2,'.',' ')+' DH');
    }

    $("body").on('click','#add-frais',function(e){
        e.preventDefault();
        $this = $(this);
        var template = $this.attr('data-prototype').replace(/__name__/g,nbrFrais);
        $("#frais-details").append(template);
        nbrFrais++;
        $("appbundle_commande").parsley();
    });

    $("body").on('click','#add-paiement',function(e){
        e.preventDefault();
        $this = $(this);
        var template = $this.attr('data-prototype').replace(/__name__/g,nbrPaiements);
        $("#paiements-details").append(template);
        $.Admin.select.activate();
        $.Admin.dateInput.initialise();
        $.Admin.controle.saisie();
        nbrPaiements++;
        $("select.mode").trigger('change');
        $("appbundle_commande").parsley();
    });

    $("body").on('click','.bind_img',function(e){
        e.preventDefault();
        $(this).closest('.form-group').find('input:file').trigger('click');
    });

    $("body").on('change','#appbundle_commande input:file,#appbundle_commande_edit input:file,#appbundle_paiement_imageCheque_file',function(){
        if (this.files.length > 0){
            $(this).closest('.form-group').find('a.pic-show').attr('href',URL.createObjectURL(this.files[0]));
            if($(this).attr('id') === 'appbundle_paiement_imageCheque_file'){
                $(this).closest('div.form-group').find('span.del-img-paiement').show();
                $(this).closest('form').find('input:hidden#appbundle_paiement_img_deleted').remove();
            }
        }
        else{
            $(this).closest('.form-group').find('a.pic-show').removeAttr('href');
            if($(this).attr('id') === 'appbundle_paiement_imageCheque_file'){
                $(this).closest('div.form-group').find('span.del-img-paiement').hide();
                $(this).closest('form').append('<input type="hidden" name="appbundle_paiement[img_deleted]" id="appbundle_paiement_img_deleted" value="1" />');

            }
        }
        $.Admin.photobox.initialise();
    });

    /*$('body').on('change','select.mode',function() {
        $this = $(this);
       if($this.find('option:selected').text() === 'Espèce' || $this.find('option:selected').text() === 'Mode paiement' ){
           $this.closest('div.paiement-line').find('div.cheque').hide();
           $this.closest('div.paiement-line').find('input.cheque-info').val('');
        }else{
           $this.closest('div.paiement-line').find('div.cheque').show();
        }
    });*/

    $("body").on('click','#delete-line',function(){
       $(this).closest('div.frais-line').remove();
       calcul();
    });

    $("body").on('click','#delete-line-paiement',function(){
        $(this).closest('div.paiement-line').remove();
        calcul();
    });

    $("body").on('click','#checkTva',function(e){
        e.preventDefault();
        $this = $(this);
        if($this.hasClass('btn-danger')){
            $this.addClass('btn-success').removeClass('btn-danger');
            $this.html('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
            $("#appbundle_commande_tva").removeAttr('readonly');
            $("#appbundle_commande_tva").val('20');
            $("#appbundle_commande_tva").focus();
        }else{
            $("#create_invoice").prop('checked',false);
            $this.addClass('btn-danger').removeClass('btn-success');
            $this.html('<i class="fa fa-circle-o" aria-hidden="true"></i>');
            $("#appbundle_commande_tva").attr('readonly','');
            $("#appbundle_commande_tva").val('');
        }
        calcul();
    });
    $("body").on('click','#enable-tva',function(e){
        e.preventDefault();
        $this = $(this);
        $input = $this.closest('div.frais-line').find('input.tva');
        if($this.hasClass('btn-danger')){
            $this.addClass('btn-success').removeClass('btn-danger');
            $this.html('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
            $input.removeAttr('readonly');
            $input.val('20');
            $input.focus();
        }else{
            $this.addClass('btn-danger').removeClass('btn-success');
            $this.html('<i class="fa fa-circle-o" aria-hidden="true"></i>');
            $input.attr('readonly','');
            $input.val('');
        }
        calcul();
    });

    $("body").on('click','#appbundle_commande button[type=submit],#appbundle_commande_edit button[type=submit]',function(){
        $("#appbundle_commande button[type=submit],#appbundle_commande_edit button[type=submit]").removeAttr('clicked');
        $(this).attr('clicked','');
    });

    $("body").on('submit','#appbundle_commande,#appbundle_commande_edit',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.find('button[type=submit][clicked]');
        var donnes = new FormData();
        $this.find('select[name],input[name],textarea[name]').each(function(){
            if ($(this).is('input:file') && $(this)[0].files.length > 0)
                donnes.append($(this).attr('name'), $(this)[0].files[0]);
            else if (!$(this).is('input:file') && $(this).is('input:checkbox') && $(this).is(':checked'))
                donnes.append($(this).attr('name'), 1);
            else if (!$(this).is('input:file') && !$(this).is('input:checkbox'))
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
                    $("#nouveau-tab").removeClass('active in');
                    $("#liste-tab").addClass('active in');
                    $("#table-commandes").DataTable().ajax.reload();
                    $("#id-span").html(json.id);
                    swal("Succès",json.msg,"success");
                    if($btn.attr('data-print') === 'true')
                        PrintVaucher(json.content,json.id);
                    // window.open(json.bon,'_blank');
                }else if(json.code === 0){
                    $(".form-error").html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });

    $("body").on('click','.delete-commande',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer cette vente ?",
                text: "Si vous supprimez cette vente vous ne pouvez pas la récupérer",
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
                            $("#table-commandes").DataTable().ajax.reload();
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


    $('#table-commandes').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
        $('div.galery').photobox('[data-photobox]',{ time:0 });
    } );

    $('body').on('DOMNodeInserted','tr.child',function(){
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
        $('div.galery').photobox('[data-photobox]',{ time:0 });
    });

    function PrintVaucher(content,numero) {
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Bon_'+numero+'</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(content);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        console.log(frame1);
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }

    /* function PrintInvaoice(content,numero) {
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>Facture_'+numero+'</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(content);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        console.log(frame1);
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }


     $("body").on('click','.print-vaucher',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        PrintDocument($this,$this.attr('data-href'));
    });

    function PrintDocument($btn,url) {
        $btn.button('loading');
        var req = new XMLHttpRequest();
        req.open("GET",url, true);
        req.responseType = "blob";
        req.onload = function (event) {
            var blob = req.response;
            var lien = window.URL.createObjectURL(blob);
            if($("iframe[name=frame1]").length)
                $("iframe[name=frame1]").remove();
            var frame1 = document.createElement('iframe');
            frame1.name = "frame1";
            frame1.setAttribute("src", lien);
            frame1.style.position = "absolute";
            frame1.style.top = "-1000000px";
            document.body.appendChild(frame1);
            setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                $btn.button('reset');
            }, 500);
        };
        req.send();
    }
*/
    $("body").on('click','.print-vaucher',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    PrintVaucher(json.content,json.id);
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    /*$("body").on('click','.print-invoice',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    PrintInvaoice(json.content,json.id);
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });*/

    $("body").on('click','.add-paiement,.edit-paiement',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.form);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.photobox.initialise();
                    $("#appbundle_paiement").parsley();
                    $("#large-modal-info").modal('hide');
                    $("#medium-modal").modal('show');
                    $('#appbundle_paiement_modePayment').trigger('change');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

   /* $("body").on('click','.bind_img',function(e){
        e.preventDefault();
        $(this).closest('.form-group').find('input:file').trigger('click');
    });

    $("body").on('change','#appbundle_paiement input:file',function(){
        if (this.files.length > 0)
            $(this).closest('.form-group').find('a.pic-show').attr('href',URL.createObjectURL(this.files[0]));
        else
            $(this).closest('.form-group').find('a.pic-show').removeAttr('href');
        $.Admin.photobox.initialise();
    });*/

    $("body").on('click','div#medium-modal #submit',function(){
        if($("#appbundle_paiement").length)
            $("#appbundle_paiement").trigger('submit');
    });

    $("body").on('submit','#appbundle_paiement',function(e){
        e.preventDefault();
        $this = $(this);
        var donnes = new FormData();
        $this.find('select[name],input[name]').each(function(){
            if ($(this).is('input:file') && $(this)[0].files.length > 0)
                donnes.append($(this).attr('name'), $(this)[0].files[0]);
            else
                donnes.append($(this).attr('name'), $(this).val());
        });
        $btn = $this.closest('div.modal').find('button#submit');
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
                    $("#medium-modal").modal('hide');
                    swal("Succès",json.msg, "success");
                    $("#table-commandes").DataTable().ajax.reload();
                }else if(json.code === 0){
                    $(".form-error").html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });

    $("body").on('click','.liste-paiements',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#large-modal-info").find('div.modal-body').html(json.html);
                    $("#large-modal-info").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.photobox.initialise();
                    $.Admin.bootstrap.toolTip();
                    $("#large-modal-info").modal('show');
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-paiement',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer ce paiement ?",
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
                            $("#large-modal-info").modal('hide');
                            swal("Succès",json.msg, "success");
                            $("#table-commandes").DataTable().ajax.reload();
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


    $("body").on('change','#appbundle_paiement_modePayment',function(){
        $this = $(this);
        if($this.find('option:selected').text() === 'Mode paiement' || $this.find('option:selected').text() === 'Espèce') {
            console.log($this.find('option:selected').text());
            $this.closest('form').find('div#no-espece').hide();
            $this.closest('form').find('div#no-espece').find('input').val('');
        }else{
            console.log($this.find('option:selected').text());
            $this.closest('form').find('div#no-espece').show();
        }
    });

    $("body").on('click','.create-invoice',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    swal({
                            title: json.msg,
                            text: "Voulez vous visualiser la facture ?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "blue",
                            confirmButtonText: "Oui",
                            cancelButtonText: "Non",
                            closeOnConfirm: true
                        },
                        function(){
                             window.open(json.lien,'_blank');
                        });
                    $("#table-commandes").DataTable().ajax.reload();
                }else if(json.code === 0){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html(json.title);
                    $.Admin.controle.saisie();
                    $("#commande_facture_tva").parsley();
                    $("#small-modal").modal('show');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','div#small-modal #submit',function(){
        if($("#commande_facture_tva").length)
            $("#commande_facture_tva").trigger('submit');
        if($("#commandes_factures_tva").length)
            $("#commandes_factures_tva").trigger('submit');
    });


    $("body").on('submit','#commande_facture_tva',function(e){
        e.preventDefault();

        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $btn.button('reset');
                    $("#small-modal").modal("hide");
                    swal({
                            title: json.msg,
                            text: "Voulez vous visualiser la facture ?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "blue",
                            confirmButtonText: "Oui",
                            cancelButtonText: "Non",
                            closeOnConfirm: true
                        },
                        function(){
                            window.open(json.lien,'_blank');
                        });
                    $("#table-commandes").DataTable().ajax.reload();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','#collection-create-invoice',function(e){
        e.preventDefault();
        if( $('#table-commandes tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des ventes avant de choisir une action !!", "error");
            return false;
        }
        $data={};
        $data['commande']={};
        $('#table-commandes tbody').find('tr.selected').each(function(index){
            $data['commande'][index] ={};
           $data['commande'][index]['id']=$(this).find('td:eq(1)').find('a:first').text();
        });
        $this = $(this);
        $btn = $this.closest('.btn-group').find('button.dropdown-toggle');
        $btn.button('loading');
        $.post(
            $this.attr('data-href'),
            $data,
            function(json){
                $btn.button('reset');
                if(json.code === 1){
                    swal({
                        title:"Succès",
                        text:json.msg,
                        type:"success",
                        html:true
                    });
                    $("#table-commandes").DataTable().ajax.reload();
                }else if(json.code === 0){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html(json.title);
                    $.Admin.controle.saisie();
                    $.Admin.bootstrap.toolTip();
                    $("#commandes_factures_tva").parsley();
                    $("#small-modal").modal('show');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('submit','#commandes_factures_tva',function(e){
        e.preventDefault();

        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function(json){
                if(json.code === 1){
                    $btn.button('reset');
                    $("#small-modal").modal("hide");
                    swal({
                        title:"Succès",
                        text:json.msg,
                        type:"success",
                        html:true
                    });
                    $("#table-commandes").DataTable().ajax.reload();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    window.Parsley.on('form:error', function() {
        if(this.$element.selector === '#commandes_factures_tva' || this.$element.selector === '#form_produits'){
            $(this.$element.selector).find('ul.parsley-errors-list').css('display','none');
        }
    });


    // L'edition



    $("body").on('click','.edit-commande',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    $this.button('reset');
                    $("#nouveau-tab").find('div.x_content').empty();
                    $("#nouveau-tab").find('div.x_content').html(json.form);
                    $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.checkInput.initialise();
                    $.Admin.photobox.initialise();
                    nbrFrais = $("div.frais-line").length + 1;
                    nbrPaiements = $("div.paiement-line").length + 1;
                    nbrProduits = $("tr.details-line").length;
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_commande_edit").parsley();
                    $("#liste-tab").removeClass('active in');
                    $("#nouveau-tab").addClass('active in');
                    $("#select_livraison").trigger('change');
                    if($("tr.details-line").length <= 1)
                        $(".delete-line-produit").prop('disabled','disabled');
                    calcul();

                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','#add-article',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        var selected = [];
        $("tr.details-line").each(function(index){
            if(!$(this).hasClass('echantillon'))
                selected.push($(this).attr('data-id'));
        });
        if(isNaN(nbrProduits))
            var index = 0;
        else
            var index = nbrProduits + 1;
        $.get(
            $this.attr('data-href'),
            {
                "selected":selected,
                "index":index
            },
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html("Ajouter des produits");
                    $("#form_produits").parsley();
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


    $("body").on('submit','#form_produits',function(e){
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

                    $("#table-produits-commandes tbody").append(json.lines);
                    $('tr.tr-alert').remove();
                    if($("tr.details-line").length > 1)
                        $(".delete-line-produit").removeAttr('disabled');
                    else
                        $(".delete-line-produit").attr('disabled','disabled');
                    nbrProduits += json.nbrLines;
                    calcul();
                }
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-line-produit',function(){
       $(this).closest('tr.details-line').remove();
       if($("tr.details-line").length <= 1)
           $(".delete-line-produit").prop('disabled','disabled');
       calcul();
    });

    $("body").on('change','#select_client_entreprise',function(){
        $this = $(this);
        $this.closest('form').find("#appbundle_commande_entreprise").remove();
        $this.closest('form').find("#appbundle_commande_client").remove();
        if($this.find("option:selected").attr('data-object') === 'client'){
            $("span#titre-client").text('Client');
            $("span.commande-client").text($this.find("option:selected").text());
            $this.closest('form').append('<input id="appbundle_commande_client" type="hidden" name="appbundle_commande[client]" value="'+$this.val()+'" />');
        }
        else if($this.find("option:selected").attr('data-object') === 'entreprise'){
            $("span#titre-client").text('Entreprise');
            $("span.commande-client").text($this.find("option:selected").text());
            $this.closest('form').append('<input id="appbundle_commande_entreprise" type="hidden" name="appbundle_commande[entreprise]" value="'+$this.val()+'" />');
        }

        if($this.find('option:selected').data('tva') === false){
            $('#checkTva').addClass('btn-danger').removeClass('btn-success');
            $('#checkTva').html('<i class="fa fa-circle-o" aria-hidden="true"></i>');
            $("#appbundle_commande_tva").attr('readonly','');
            $("#appbundle_commande_tva").val('');
        }else if($this.find('option:selected').data('tva') === true){
            $('#checkTva').addClass('btn-success').removeClass('btn-danger');
            $("#checkTva").html('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
            $("#appbundle_commande_tva").removeAttr('readonly');
            $("#appbundle_commande_tva").val('20');
            $("#appbundle_commande_tva").focus();
        }


        calcul();

        $("#latest-commandes").empty();
        $data = {};
        $data[$this.find("option:selected").attr('data-object')]=$this.val();
        if($("#latest-commandes").attr('data-except'))
            $data['except'] = $("#latest-commandes").attr('data-except');
        $dataToSend = {'criteres':$data};
        $.post(
            $("#latest-commandes").attr('data-href'),
            $dataToSend,
            function(json){
                if(json.code === 1){
                   $("#latest-commandes").html(json.content);
                   $("#latest-commandes").find('h5').html('Les dernières ventes pour <span style="color: blue;">' + $this.find("option:selected").text()+'</span>');
                   $.Admin.bootstrap.toolTip();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });

    });

    $("body").on('change','#select_livraison',function(){
        $("#employe-span").empty();
        $(this).closest('form').find("#appbundle_commande_livraisonEntreprise").remove();
        $(this).closest('form').find("#appbundle_commande_livraisonClient").remove();
        $(this).closest('form').find("#appbundle_commande_employe").remove();
        if($(this).val() === '')
            return false;
        if($(this).find("option:selected").attr('data-object') === 'client')
            $(this).closest('form').append('<input id="appbundle_commande_livraisonClient" type="hidden" name="appbundle_commande[livraisonClient]" value="'+$(this).val()+'" />');
        else if($(this).find("option:selected").attr('data-object') === 'entreprise')
            $(this).closest('form').append('<input id="appbundle_commande_livraisonEntreprise" type="hidden" name="appbundle_commande[livraisonEntreprise]" value="'+$(this).val()+'" />');
        else if($(this).find("option:selected").attr('data-object') === 'employe')
            $(this).closest('form').append('<input id="appbundle_commande_employe" type="hidden" name="appbundle_commande[employe]" value="'+$(this).val()+'" />');
        if($(this).val() && $(this).val() !== '')
            $("#employe-span").html($(this).find('option:selected').text());
    });



    $("body").on('click','#add-echantillon',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        var selected = [];
        $("tr.details-line.echantillon").each(function(index){
            selected.push($(this).attr('data-id'));
        });
        if(isNaN(nbrProduits))
            var index = 0;
        else
            var index = nbrProduits + 1;
        $.get(
            $this.attr('data-href'),
            {
                "selected":selected,
                "index":index
            },
            function(json){
                if(json.code === 1){
                    $("#large-modal").find('div.modal-body').html(json.form);
                    $("#large-modal").find('.modal-title').html("Ajouter des echantillons");
                    $("#form_echantillons").parsley();
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


    $("body").on('submit','#form_echantillons',function(e){
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
                    $("#table-produits-commandes tbody").append(json.lines);
                    $('tr.tr-alert').remove();
                    nbrProduits += json.nbrLines;
                    if($("tr.details-line").length > 1)
                        $(".delete-line-produit").removeAttr('disabled');
                    else
                        $(".delete-line-produit").attr('disabled','disabled');
                    calcul();
                }
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','#toggle-remise',function(e){
        e.preventDefault();
        $this = $(this);
        if($this.hasClass('btn-danger')){
            $this.addClass('btn-success').removeClass('btn-danger');
            $this.html('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
            $("#appbundle_commande_remise").removeAttr('readonly');
            $("#appbundle_commande_remise").val('10');
            $("#appbundle_commande_remise").focus();
        }else{
            $this.addClass('btn-danger').removeClass('btn-success');
            $this.html('<i class="fa fa-circle-o" aria-hidden="true"></i>');
            $("#appbundle_commande_remise").attr('readonly','');
            $("#appbundle_commande_remise").val('');
        }
        calcul();
    });


    $("body").on('click','#btn-regle',function(e){
       e.preventDefault();
       $input = $(this).closest("div.modal-body").find("#appbundle_paiement_prix");
       $input.val(parseFloat($input.attr('max')).toFixed(2));
    });


    $("body").on('click','#collection-add-paiment',function(e){
        e.preventDefault();
        if( $('#table-commandes tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des ventes avant de choisir une action !!", "error");
            return false;
        }
        $data={};
        $data['commandes']={};
        $('#table-commandes tbody').find('tr.selected').each(function(index){
            $data['commandes'][index] =$(this).find('td:eq(1)').find('a:first').text();
        });
        $this = $(this);
        $btn = $this.closest('.btn-group').find('button.dropdown-toggle');
        $btn.button('loading');
        $.post(
            $this.attr('data-href'),
            $data,
            function(json){
                $btn.button('reset');
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.form);
                    $("#medium-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.photobox.initialise();
                    $.Admin.bootstrap.toolTip();
                    $("#appbundle_paiement").parsley();
                    $("#large-modal-info").modal('hide');
                    $("#medium-modal").modal('show');
                    $('#appbundle_paiement_modePayment').trigger('change');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });


    function autoRun(){
        if($("#auto-run").length){
            $this = $("#auto-run:first");
            if($this.attr('data-operation') === 'edit'){
                $.get(
                    $this.attr('data-href'),
                    {},
                    function(json){
                        if(json.code === 1){
                            $("#nouveau-tab").find('div.x_content').html(json.form);
                            $("#nouveau-tab").find('#h2-title').html( $this.attr('data-title'));
                            $.Admin.select.activate();
                            $.Admin.controle.saisie();
                            $.Admin.dateInput.initialise();
                            $.Admin.checkInput.initialise();
                            $.Admin.photobox.initialise();
                            nbrFrais = $("div.frais-line").length + 1;
                            nbrPaiements = $("div.paiement-line").length + 1;
                            nbrProduits = $("tr.details-line").length;
                            autosize($('textarea.auto-growth'));
                            $("#appbundle_commande_edit").parsley();
                            $("#liste-tab").removeClass('active in');
                            $("#nouveau-tab").addClass('active in');
                            $("#select_livraison").trigger('change');
                            $("#select_client_entreprise").trigger('change');
                            if($("tr.details-line").length <= 1)
                                $(".delete-line-produit").prop('disabled','disabled');
                            calcul();
                        }
                    },
                    "JSON"
                ).fail(function ($xhr) {
                    swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                });
            }
        }
    }

    autoRun();


    $("body").on('change','#create_invoice',function(){
       if($(this).is(':checked')){
           if($("#checkTva").hasClass('btn-danger')){
               $("#checkTva").addClass('btn-success').removeClass('btn-danger');
               $("#checkTva").html('<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
               $("#appbundle_commande_tva").removeAttr('readonly');
               $("#appbundle_commande_tva").val('20');
               $("#appbundle_commande_tva").focus();
           }
           calcul();
       }
    });


    $("body").on('click','span.statut_client_entreprise',function(){
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.data('href'),
            {
                'type':$this.data('type'),
                'id':$this.data('id')
            },
            function(json){
                $this.button('reset');
                if(json.code === 1){
                    $("#medium-modal").find('div.modal-body').html(json.form);
                    $("#medium-modal").find('.modal-title').html($this.data('title'));
                    $.Admin.select.activate();
                    $("#medium-modal").modal('show');
                    $("#form_statut").parsley();
                    $('.date').datetimepicker({
                        format: 'DD/MM/YYYY',
                        locale: 'fr'
                    });
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('change','.check-type-report',function(){
        var id = $(this).val();
        if(id === 'month'){
            $('div#periode').hide();
            $('div#month').show();
            $('#form_statut').find('select#statut_annee').attr('name',$('#form_statut').find('select#statut_annee').data('name'));
            $('#form_statut').find('select#statut_mois').attr('name',$('#form_statut').find('select#statut_mois').data('name'));
            $('#form_statut').find('input:text.date').removeAttr('required').removeAttr('name');
        }else if(id === 'periode'){
            $('div#month').hide();
            $('div#periode').show();
            $('#form_statut').find('select#statut_annee,select#statut_mois').removeAttr('name');
            $('#form_statut').find('input:text.date').attr('required','required');
            $('#form_statut').find('#statut_date_end').attr('name',$('#form_statut').find('#statut_date_end').data('name'));
            $('#form_statut').find('#statut_date_start').attr('name',$('#form_statut').find('#statut_date_start').data('name'));
        }
    });

    $("body").on('click','.del-img-paiement',function(){
        $this = $(this);
        $this.closest('.form-group').find('a.pic-show').removeAttr('href');
        $.Admin.photobox.initialise();
        $this.closest('form').append('<input type="hidden" name="appbundle_paiement[img_deleted]" id="appbundle_paiement_img_deleted" value="1" />');
        $this.hide();
    });



    $("body").on('click','#collection-delete',function(e){
        e.preventDefault();
        $this = $(this);
        if( $('#table-commandes tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des ventes avant de choisir une action !!", "error");
            return false;
        }
        swal({
                title: "Voullez-vous supprimer ces ventes ?",
                text: "Si vous supprimez ces ventes vous ne pouvez pas les récupérer",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "Oui, supprimer!",
                closeOnConfirm: true
            },
            function(){
                $data={};
                $data['commandes']={};
                $('#table-commandes tbody').find('tr.selected').each(function(index){
                    $data['commandes'][index] =$(this).find('td:eq(1)').find('a:first').text();
                });
                $btn = $this.closest('.btn-group').find('button.dropdown-toggle');
                $btn.button('loading');
                $.post(
                    $this.attr('data-href'),
                    $data,
                    function(json){
                        $btn.button('reset');
                        if(json.code === 1){
                            swal("Succès",json.msg, "success");
                            $("#table-commandes").DataTable().ajax.reload();
                        }
                    },
                    "JSON"
                ).fail(function ($xhr) {
                    $btn.button('reset');
                    swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                });
            });
    });

    $("body").on('click','#collection-factures-print',function(e){
        e.preventDefault();
        if( $('#table-commandes tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des commandes avant de choisir une action !!", "error");
            return false;
        }
        var client = $('#table-commandes tbody').find('tr.selected:first').find('td:eq(3)').find('span:first').text();
        console.log(client);
        var has_error = false;
        var factures=[];

        $('#table-commandes tbody').find('tr.selected').each(function(index){
            if($(this).find('td:eq(7)').find('a').length){
                factures.push($(this).find('td:eq(7)').find('a:first').text());
                if(client !== $(this).find('td:eq(3)').find('span:first').text())
                    has_error = true;
            }
        });
        if(has_error === true){
            swal("Erreur","Vous devez séléctionner des commandes pour un seul client !!", "error");
            return false;
        }
        if(factures.length === 0){
            if(has_error === true){
                swal("Erreur","Vous devez séléctionner des commandes avec facture !!", "error");
                return false;
            }
        }
        var url = $(this).attr('data-href');

        for(var i = 0;i < factures.length;i++){
            if(i === 0)
                url += '?ids[]='+  factures[i];
            else
                url += '&ids[]='+  factures[i];
        }
        console.log('Url : ');
        console.log(url);
        window.open(url, '_blank');
    });

    $("body").on('click','#collection-bons-print',function(e){
        e.preventDefault();
        if( $('#table-commandes tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des commandes avant de choisir une action !!", "error");
            return false;
        }
        var client = $('#table-commandes tbody').find('tr.selected:first').find('td:eq(3)').find('span:first').text();
        console.log(client);
        var has_error = false;
        var factures=[];

        $('#table-commandes tbody').find('tr.selected').each(function(index){
                factures.push($(this).find('td:eq(1)').find('a:first').text());
                if(client !== $(this).find('td:eq(3)').find('span:first').text())
                    has_error = true;
        });
        if(has_error === true){
            swal("Erreur","Vous devez séléctionner des commandes pour un seul client !!", "error");
            return false;
        }
        var url = $(this).attr('data-href');

        for(var i = 0;i < factures.length;i++){
            if(i === 0)
                url += '?ids[]='+  factures[i];
            else
                url += '&ids[]='+  factures[i];
        }
        console.log('Url : ');
        console.log(url);
        window.open(url, '_blank');
    });

    $("body").on('click','#collection-create-one-invoice',function(e){
        e.preventDefault();
        if( $('#table-commandes tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des ventes avant de choisir une action !!", "error");
            return false;
        }
        var client = $('#table-commandes tbody').find('tr.selected:first').find('td:eq(3)').find('span:first').text();
        console.log(client);
        var has_error = false;
       $('#table-commandes tbody').find('tr.selected').each(function(index){
            if(client !== $(this).find('td:eq(3)').find('span:first').text())
                has_error = true;
        });
        if(has_error === true){
            swal("Erreur","Vous devez séléctionner des commandes pour un seul client !!", "error");
            return false;
        }
        $data={};
        $data['commande']={};
        $('#table-commandes tbody').find('tr.selected').each(function(index){
            $data['commande'][index] ={};
            $data['commande'][index]['id']=$(this).find('td:eq(1)').find('a:first').text();
        });
        $this = $(this);
        $btn = $this.closest('.btn-group').find('button.dropdown-toggle');
        $btn.button('loading');
        $.post(
            $this.attr('data-href'),
            $data,
            function(json){
                $btn.button('reset');
                if(json.code === 1){
                    swal({
                        title:"Succès",
                        text:json.msg,
                        type:"success",
                        html:true
                    });
                    $("#table-commandes").DataTable().ajax.reload();
                }else if(json.code === 0){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html(json.title);
                    $.Admin.controle.saisie();
                    $.Admin.bootstrap.toolTip();
                    $("#commandes_factures_tva").parsley();
                    $("#small-modal").modal('show');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });


    $("body").on('click','.remove-invoice',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous enlever ce  bon de la facture ?",
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
                            $("#table-commandes").DataTable().ajax.reload();
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

});