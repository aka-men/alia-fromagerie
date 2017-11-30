/**
 * Created by abdelhak on 01/07/2017.
 */


$(document).ready(function(){

    var datasets = [];
    var title = {};

    $("#table-paiements").on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
        $.Admin.btnTable.style();
    } );

    $("#table-paiements").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "ajax": {
            "url": $("#table-paiements").attr('data-href'),
            "type": "POST",
        },
        "columns": [
            {"data": "ligne"},
            {"data": "date"},
            {"data": "client"},
            {"data": "commande"},
            {"data": "montant"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,2,5]},
            {className: "bg-ligne", "targets": 0},
            {className: "text-center", "targets": [5,3]}
        ],
        "order": [[ 1, "ASC" ]],
        "pageLength": 5,
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

    $("body").on('click','.valider-paiement',function(){
        $this = $(this);
        $this.button('loading');
        $.post(
            $this.attr('data-href'),
            {},
            function(json){
                if(json.code === 1){
                    swal("Succès",json.msg, "success");
                    $("#table-paiements").DataTable().ajax.reload();
                }
                $this.button('reset');
            },
            'JSON'
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    });

    $('#table-commandes').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info":false,
        "ajax": {
            "url": $('#table-commandes').attr('data-href'),
            "type": "POST"
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "date"},
            {"data": "client"},
           {"data": "total"},
            {"data": "avance"},
            {"data": "reste"}
        ],
        columnDefs: [
            {orderable: false, targets: [3,4,5,6]},
            {className: "text-center", "targets": [4,5,6]},
            {className: "red-text", "targets": 6},
            {className: "green-text", "targets": 5},
            {className: "bg-ligne", "targets": 0}
        ],
        "order": [[ 1, "ASC" ]],
        "pageLength": 5,
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

    $('#table-commandes').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );

    $("#table-reglements").DataTable({
        responsive: true,
        "bFilter": false,
        'info': false,
        "columns": [
            {"data": "ligne"},
            {"data": "date"},
            {"data": "numero"},
            {"data": "type"},
            {"data": "montant"}
        ],
        "order": [[ 1, "ASC" ]],
        "pageLength": 5,
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

    $('#table-reglements').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );


    $("#table-types-non-paye").DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        'info': false,
        "createdRow": function ( row, data, index ) {
            if($(row).find('td:last').find('button').attr('data-type') === 'autre')
                $(row).find('td:first').addClass('select').css('cursor','pointer');
            },
        "ajax": {
            "url": $("#table-types-non-paye").attr('data-href'),
            "type": "POST",
        },
        "columns": [
            {"data": "ligne"},
            {"data": "label"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,2]},
            {className: "bg-ligne text-center", "targets": 0},
            {className: "text-center", "targets": 2},
            { "width": "20px", "targets": 0 }
        ],
        "order": [[ 1, "ASC" ]],
        "pageLength": 5,
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

    $("body").on('click','.paye-charge',function(){
      $this = $(this);
      if($this.attr('data-type') === 'autre')
          var url = $this.attr('data-href')+'?types[]='+$this.attr('data-id');
      else if($this.attr('data-type') === 'employe')
          var url = $this.attr('data-href')+'?type='+$this.attr('data-id');
      $(location).attr('href',url);
    });

    $('#table-types-non-paye tbody').on( 'click', 'td.select', function () {
        $(this).closest('tr').toggleClass('selected');
        if( $('#table-types-non-paye tbody').find('tr.selected').length > 0)
            $("#paye-charges").show();
        else
            $("#paye-charges").hide();
     });

    $("body").on('click','#paye-charges',function(){
        $this = $(this);
        if( $('#table-types-non-paye tbody').find('tr.selected').length === 0){
            swal("Erreur","Veuillez choisir au moins une charge !!", "error");
            return false;
        }

        var hasError = false;
        var type =  $('#table-types-non-paye tbody').find('tr.selected:first').find('td:last').find('button.paye-charge').attr('data-type');
        var url = $this.attr('data-href-'+type);
        var firstLoop = true;
        $("#table-types-non-paye tbody tr.selected").each(function(index){
            console.log("boucle type : "+ $(this).find('td:last').find('button.paye-charge').attr('data-type'));
            if(type !== $(this).find('td:last').find('button.paye-charge').attr('data-type'))
                hasError = true;
            if(firstLoop){
                url += '?types[]='+$(this).find('td:last').find('button.paye-charge').attr('data-id');
                firstLoop = false;
            }
            else
                url += '&types[]='+$(this).find('td:last').find('button.paye-charge').attr('data-id');
        });
        if(hasError === true){
            swal("Erreur","Veuillez choisir des charges du même type (charge employé,autre) !!", "error");
            return false;
        }
        $(location).attr('href',url);
    });

    var months = [{'long':'Janvier', 'short':'janv'}, {'long':'Février', 'short':'févr'}, {'long':'Mars', 'short':'mars'}, {'long':'Avril', 'short':'avr'}, {'long':'Mai', 'short':'mai'}, {'long':'Juin', 'short':'juin'}, {'long':'Juillet', 'short':'juil'}, {'long':'Août', 'short':'août'}, {'long':'Septembre', 'short':'sept'}, {'long':'Octobre', 'short':'oct'}, {'long':'Novembre', 'short':'nov'}, {'long':'Décembre','short':'déc'}];
    function loadChartGlobale() {
        var config = {
            type: 'line',
            data: {
                labels: AxesY,
                datasets: [{
                    label: "Achats",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: dataAchats,
                    fill: false,
                }, {
                    label: "Depenses",
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: dataCharges,
                },{
                    label: "Ventes",
                    fill: false,
                    backgroundColor: window.chartColors.orange,
                    borderColor: window.chartColors.orange,
                    data: dataVentes,
                },{
                    label: "Charges Employés",
                    fill: false,
                    backgroundColor: window.chartColors.purple,
                    borderColor: window.chartColors.purple,
                    data: dataChargesEmployes,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Totaux des 12 dérniers mois'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Mois'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Montant (DH)'
                        }
                    }]
                }
            }
        };

           var ctx = document.getElementById("chart").getContext("2d");
            window.myLine = new Chart(ctx, config);


    }

    function loadDataProduction(href,mois,annee,init) {
        $table = $("#table-produits");
        $table.find('thead tr:first th:gt(0)').remove();
        $table.find('tbody tr.ventes td:gt(0)').remove();
        $table.find('tbody tr.achats td:gt(0)').remove();
        $table.find('tbody tr.production td:gt(0)').remove();
        //$("canvas#chart-prod-vente").remove();
        //$("#content-canvas").append('<canvas style="max-height: 500px;" id="chart-prod-vente"></canvas>');
        $.get(
            href,
            {
                'month':mois,
                'year':annee
            },
            function(json){
                if(json.code === 1){
                    $("#titre-table").html('Achats - Ventes - Production ('+ json.mois +' - '+ json.annee +')');
                    $.each(json.achats,function(index,line){
                        $table.find('thead tr:first').append('<th style="font-size: 10px;">'+line.produit+'</th>');
                        if(line.unite === null)
                            var unite = '';
                        else
                            var unite = line.unite;
                        $table.find('tbody tr.achats').append('<td style="text-align: center;vertical-align: middle;">'+line.achats+' '+unite+'</td>');
                    });
                    $.each(json.ventes,function(index,line){
                        if(line.unite === null)
                            var unite = '';
                        else
                            var unite = line.unite;
                        $table.find('tbody tr.ventes').append('<td style="text-align: center;vertical-align: middle;">'+line.ventes+' '+unite+'</td>');
                    });
                    $.each(json.production,function(index,line){
                        if(line.unite === null)
                            var unite = '';
                        else
                            var unite = line.unite;
                        $table.find('tbody tr.production').append('<td style="text-align: center;vertical-align: middle;">'+line.production+' '+unite+'</td>');
                    });

                    var dataVentesProduits = [];
                    var dataAchatsProduits = [];
                    var dataProductionProduits = [];
                    var labels = [];

                    $.each(json.achats,function(index,line){
                        dataAchatsProduits.push(parseFloat(line.achats));
                        labels.push(line.produit);
                    });
                    $.each(json.ventes,function(index,line){
                        dataVentesProduits.push(parseFloat(line.ventes));
                    });
                    $.each(json.production,function(index,line){
                        dataProductionProduits.push(parseFloat(line.production));
                    });
                    datasets.pop();
                    datasets.pop();
                    datasets.pop();
                    datasets.push({label: "Achats", backgroundColor: 'rgb(252, 215, 112)', borderColor: 'rgb(252, 215, 112)', data: dataAchatsProduits, fill: false,});
                    datasets.push({label: "Production", fill: false, backgroundColor: 'rgb(153,252,125)', borderColor: 'rgb(153,252,125)', data: dataProductionProduits,});
                    datasets.push({label: "Ventes", fill: false, backgroundColor: 'rgb(231, 124, 245)', borderColor: 'rgb(231, 124, 245)', data: dataVentesProduits,});

                    title.display = true;
                    title.text = 'Achats - Ventes - Production ('+ json.mois +' - '+ json.annee +')';

                    var config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: datasets
                            },
                            options: {
                                responsive: true,
                                title:title,
                                tooltips: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                hover: {
                                    mode: 'nearest',
                                    intersect: true
                                },
                                scales: {
                                    xAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Produits'
                                        }
                                    }],
                                    yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Quantité'
                                        }
                                    }]
                                }
                            }
                        };
                        if (init === true){
                            console.log("init");
                            var ctx1 = document.getElementById("chart-prod-vente").getContext("2d");
                            window.myBar = new Chart(ctx1, config);
                        }else{
                            console.log("update");
                            window.myBar.config.options.title.text = title.text;
                            window.myBar.update();
                        }
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
        });
    }

    loadDataProduction($("#root-select-mois").attr('data-href'),$("#root-select-mois").attr('data-month'),$("#root-select-mois").attr('data-year'),true);

   loadChartGlobale();


   $("body").on('click','.select-mois',function(){
       $this = $(this);
      $("#root-select-mois").html($this.text()+' <span class="caret"></span>');
      loadDataProduction( $("#root-select-mois").attr('data-href'),$this.attr('data-month'),$this.attr('data-year'),false);
   });


});