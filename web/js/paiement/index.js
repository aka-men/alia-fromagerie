/**
 * Created by abdelhak on 24/08/2017.
 */



$(document).ready(function(){


    $('#table-paiements').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info":false,
        "createdRow": function ( row, data, index ) {

        },
        "ajax": {
            "url": $('#table-paiements').attr('data-href'),
            "type": "POST",
            "data": function (d) {
                var criteres = new Object();
                if($(".filter#numeroReglement").val() !=='')
                    criteres['numeroReglement']=$(".filter#numeroReglement").val();
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
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "date"},
            {"data": "commande"},
            {"data": "client"},
            {"data": "montant"},
            {"data": "mode"},
            {"data": "numero"}
        ],
        columnDefs: [
            {orderable: false, targets: [0,3,2]},
            {className: "text-center", "targets": [1,4]},
            {className: "bg-ligne text-center", "targets": 0}
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
        $("#table-paiements").DataTable().ajax.reload();
    });
    $("#length").on('change',function(){
        $('#table-paiements').DataTable().page.len($("#length").val()).draw();
    });

    $('#table-paiements').on( 'draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    } );


});