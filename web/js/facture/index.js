/**
 * Created by abdelhak on 24/06/2017.
 */

$(document).ready(function(){


    $('#table-factures').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info":false,
        "createdRow": function ( row, data, index ) {
            if (parseFloat(data.avanceNum) > 0) {
                $(row).find('td:eq(10)').css({
                    'color': 'green'
                });
            }
            if(parseFloat(data.reste) > 0 ){
                $(row).find('td:eq(11)').css({
                    'color': 'red'
                });
            }
        },
        "ajax": {
            "url": $('#table-factures').attr('data-href'),
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
                        else if(!$(this).is("input:checkbox") && $(this).val() !== '')
                            criteres[$(this).attr('id')]=$(this).val();
                    });
                }
                d.criteres = criteres;
            },
            "dataSrc" : function (json) {
                $("div#totals").remove();
                $("div#produits").remove();
                $("#table-factures_wrapper").children('div.row:eq(1)').after(json.totals);
                $("div#totals").after(json.produits);
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "commande"},
            {"data": "id"},
            {"data": "date"},
            {"data": "client"},
            {"data": "ht"},
            {"data": "tva"},
            {"data": "ttc"},
            {"data": "frais"},
            {"data": "total"},
            {"data": "avance"},
            {"data": "reste"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,4,12,11,8,9,10]},
            {className: "text-center", "targets": [9,5,6,7,8,10,11]},
            {className: "bg-ligne select pointer", "targets": 0}
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
        $this = $(this);
        if($this.attr('id') !== 'mois' && $this.attr('id') !== 'annee' && $this.val() !== ''){
            $('.filter#mois').selectpicker('val','');
            $('.filter#annee').selectpicker('val','');
        }
        $("#table-factures").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $('#table-factures').DataTable().page.len($("#length").val()).draw();
    });

    $('#table-factures').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );

    $('body').on('DOMNodeInserted','tr.child',function(){
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    });


    $('#table-factures tbody').on( 'click', 'td.select', function () {
        $(this).closest('tr').toggleClass('selected');
        if( $('#table-factures tbody').find('tr.selected').length > 0)
            $("#collection-action").show();
        else
            $("#collection-action").hide();
        if(allInvoiceChecked() === true)
            $("#select-all-invoice").prop("checked",true);
        else
            $("#select-all-invoice").prop("checked",false);
    });

    function allInvoiceChecked() {
        var allChecked = true;
        $('#table-factures tbody tr').each(function(index){
            if(!$(this).hasClass('selected'))
                allChecked = false;
        });
        return allChecked;
    }

    $("body").on('change',"#select-all-invoice",function(){
        if($(this).is(":checked"))
            $('#table-factures tbody tr').addClass('selected');
        else
            $('#table-factures tbody tr').removeClass('selected');
        if( $('#table-factures tbody').find('tr.selected').length > 0)
            $("#collection-action").show();
        else
            $("#collection-action").hide();
    });


    $("body").on('click','#collection-print',function(e){
        e.preventDefault();
        if( $('#table-factures tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des factures avant de choisir une action !!", "error");
            return false;
        }
        var client = $('#table-factures tbody').find('tr.selected:first').find('td:eq(4)').find('span:first').text();
        console.log(client);
        var has_error = false;
        var factures=[];

        $('#table-factures tbody').find('tr.selected').each(function(index){
            factures.push($(this).find('td:eq(2)').find('a:first').data('id'));
            if(client !== $(this).find('td:eq(4)').find('span:first').text())
                has_error = true;
        });
        if(has_error === true){
            swal("Erreur","Vous devez séléctionner des factures pour un seul client !!", "error");
            return false;
        }
        var url = $(this).attr('data-href');

        for(var i = 0;i < factures.length;i++){
            if(i === 0)
                url += '?ids[]='+  factures[i];
            else
                url += '&ids[]='+  factures[i];
        }

        window.open(url, '_blank');
    });


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

    $("body").on('click','div#medium-modal #submit',function(){
        if($("#appbundle_paiement").length)
            $("#appbundle_paiement").trigger('submit');
        if($("#form_statut").length)
            $("#form_statut").trigger('submit');
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
                    $("#table-factures").DataTable().ajax.reload();
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
                            $("#table-factures").DataTable().ajax.reload();
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

    $("body").on('click','#btn-regle',function(e){
        e.preventDefault();
        $input = $(this).closest("div.modal-body").find("#appbundle_paiement_prix");
        $input.val(parseFloat($input.attr('max')).toFixed(2));
    });


    $("body").on('click','#collection-add-paiment',function(e){
        e.preventDefault();
        if( $('#table-factures tbody').find('tr.selected').length === 0){
            swal("Erreur","Vous devez séléctionner des factures avant de choisir une action !!", "error");
            return false;
        }
        $data={};
        $data['commandes']={};
        $('#table-factures tbody').find('tr.selected').each(function(index){
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


    $("body").on('click','.collection-add-paiment',function(e){
        e.preventDefault();
        $this = $(this);
        $data={};
        data_commandes = $this.closest('tr').find('td:eq(1)').children('a').data('commandes');
        $data['commandes'] = data_commandes.toString().split('-');
        var url = $this.attr('data-href');
        if($data['commandes'].length === 1){
            url = $this.attr('data-href-one');
            url = url.toString().replace('id_commande',$data['commandes'][0]);
            $data = {};
        }
        $this.button('loading');
        $.post(
            url,
            $data,
            function(json){
                $this.button('reset');
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
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $("body").on('click','.delete-facture',function(){
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer cette Facture ?",
                text: "Si vous supprimez cette facture vous ne pouvez pas la récupérer",
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
                            $("#table-factures").DataTable().ajax.reload();
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
