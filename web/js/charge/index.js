$(document).ready(function () {

    $('#table-charges').DataTable({
        responsive: true,
        "processing": true,
        "bFilter": false,
        "serverSide": true,
        "info": false,
        "createdRow": function (row, data, index) {
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
                if ($(".filter#id").val() !== '')
                    criteres['id'] = $(".filter#id").val();
                else {
                    $(".filter").each(function () {
                        if ($(this).val() !== '')
                            criteres[$(this).attr('id')] = $(this).val();
                    });
                }
                d.criteres = criteres;
            },
            "dataSrc": function (json) {
                $("div#totals").remove();
                $("#table-charges_wrapper").children('div.row:eq(1)').after(json.totals);
                return json.data;
            }
        },
        "columns": [
            {"data": "ligne"},
            {"data": "id"},
            {"data": "date"},
            {"data": "fournisseur"},
            {"data": "type"},
            {"data": "montant"},
            {"data": "mode"},
            {"data": "cheque"},
            {"data": "dateCheque"},
            {"data": "facture"},
            {"data": "tva"},
            {"data": "observation"},
            {"data": "action"}
        ],
        columnDefs: [
            {orderable: false, targets: [0, 4, 12]},
            {className: "text-center", "targets": [5, 10]},
            {className: "bg-ligne", "targets": 0}
        ],
        "order": [[2, "desc"]],
        "pageLength": $("#length").val(),
        "bLengthChange": false,
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

    $("body").on('click', '.delete-charge', function () {
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
            function () {
                $this.button('loading');
                $.post(
                    $this.attr('data-href'),
                    {},
                    function (json) {
                        if (json.code === 1) {
                            swal("Succès", json.msg, "success");
                            $("#table-charges").DataTable().ajax.reload();
                        }
                        $this.button('reset');
                    },
                    "JSON"
                ).fail(function ($xhr) {
                    $this.button('reset');
                    swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
                });
            });
    });

    $(".filter").on('change keyup', function () {
        $("#table-charges").DataTable().ajax.reload();
    });
    $("#length").on('change', function () {
        $("#table-charges").DataTable().page.len($("#length").val()).draw();
    });


    $("#add-charge").on('click', function (e) {
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function (json) {
                $this.button('reset');
                if (json.code === 1) {
                    $("#nouveau-tab").find('div.x_content').html(json.form);
                    $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.checkInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("#appbundle_charge").parsley();
                    if ($("div.line-charge-autre").length > 0)
                        makeOtherFormRequired();
                    makeFirstUndeleted();
                    $("#liste-tab").removeClass('active in');
                    $("#nouveau-tab").addClass('active in');
                    $("select.fournisseur").trigger('change');
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    $("body").on('click', '.bind_img', function (e) {
        e.preventDefault();
        $(this).closest('.input-group').find('input:file').trigger('click');
    });

    $("body").on('change', 'input:file.cheque,input:file.facture', function () {
        /* $btn = $(this).prev('button.bind_img');
         if(this.files.length>0){
         $btn.html('<img class="img-responsive" style="width: 80px;" src="'+URL.createObjectURL(this.files[0])+'" />');
         $btn.css("padding","0");
         }else{
         $btn.html('Choisir photo');
         $btn.css("padding","");
         }*/
    })


    $("body").on('submit', '#appbundle_charge', function (e) {
        e.preventDefault();
        $this = $(this);
        var donnes = new FormData();
        $this.find('div.line-charge-autre').each(function () {
            $(this).find('select,input[name],textarea').each(function () {
                if ($(this).is('select[multiple]')) {
                    $select = $(this);
                    $(this).find('option:selected').each(function (index) {
                        donnes.append($select.attr('name') + '[]', $(this).val());
                    });
                } else if ($(this).is('input:file') && $(this)[0].files.length > 0)
                    donnes.append($(this).attr('name'), $(this)[0].files[0]);
                else if (!$(this).is('input:file') && !$(this).is('input:checkbox'))
                    donnes.append($(this).attr('name'), $(this).val());
                else if ($(this).is('input:checkbox') && $(this).is(':checked'))
                    donnes.append($(this).attr('name'), '1');
            });
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
            success: function (json) {
                if (json.code === 1) {
                    $("#liste-charges").tab("show");
                    swal({
                        title: "Succès",
                        text: json.msg,
                        type: "success",
                        html: true
                    });
                    $("#table-charges").DataTable().ajax.reload();
                    $("#nouveau-tab").removeClass('active in');
                    $("#liste-tab").addClass('active in');
                } else if (json.code === 0) {
                    $("#form-error").html(json.msg.replace(/ERROR:/g, '<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
                $btn.button('reset');
            }
        });
    });

    $("#add-charge-employe,#add-charge").on('hidden.bs.tab', function () {
        $("#nouveau-tab").find('div.x_content').empty();
    });

    function makeOtherFormRequired() {
        $("div.line-charge-autre").each(function () {
            $(this).find('input.required').attr('required', 'required');
        });
        $("#appbundle_charge").parsley().destroy();
        $("#appbundle_charge").parsley();
    }

    function getIndex() {
        var i = 0;
        $('.line-charge-autre').each(function () {
            if (parseInt($(this).data('index')) > i)
                i = parseInt($(this).data('index'));
        });
        return (i + 1);
    }

    $("body").on('click', '.clone-charge', function () {
        $this = $(this);
        var i = $this.closest('div.line-charge-autre').data('index');
        var $ligne = $this.closest('div.line-charge-autre').clone();
        var newI = getIndex();
        $ligne.attr('data-index',getIndex());
        $ligne.find('div.has-select').each(function(){
           var $select = $(this).find('select').clone();
           $(this).empty();
           $(this).append($select);
        });
        $ligne.find('select.fournisseur option').each(function(){
           if($(this).attr('value') === $this.closest('div.line-charge-autre').find('select.fournisseur').val())
               $(this).prop('selected',true);
        });
        $ligne.find('select.type option').each(function(){
            if($.inArray($(this).attr('value').toString(),$this.closest('div.line-charge-autre').find('select.type').val()) >= 0){
                 $(this).prop('selected',true);
            }
        });
        $ligne.find('input.cheque').val('');
        $ligne.find('input.date-input').removeClass('hasDatepicker');
        $ligne.find('select,input,textarea').each(function () {
            var elementId = $(this).attr('id');
            var elementName = $(this).attr('name');
            var elementDataName = $(this).attr('data-name');
            if (typeof elementId != 'undefined')
                $(this).attr('id',elementId.replace(i,newI));
            if (typeof elementName != 'undefined')
                $(this).attr('name',elementName.replace(i,newI));
            if (typeof elementDataName != 'undefined')
                $(this).attr('data-name',elementDataName.replace(i,newI));
        });
        $('div.line-charge-autre:last').after($ligne);
        $.Admin.select.activate();
        $.Admin.controle.saisie();
        $.Admin.dateInput.initialise();
        $.Admin.checkInput.initialise();
        autosize($('textarea.auto-growth'));
        makeOtherFormRequired();
        makeFirstUndeleted();
    });

    $("body").on('click', '#add-line-charge', function (e) {
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {
                'index': getIndex()
            },
            function (json) {
                $this.button('reset');
                if (json.code === 1) {
                    $('div.line-charge-autre:last').after(json.line);
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $.Admin.dateInput.initialise();
                    $.Admin.checkInput.initialise();
                    autosize($('textarea.auto-growth'));
                    $("div.line-charge-autre:last").find('select.fournisseur').trigger('change');
                    makeOtherFormRequired();
                    makeFirstUndeleted();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
            $this.button('reset');
        });
    });

    function makeFirstUndeleted() {
        if ($('div.line-charge-autre').length === 1) {
            $('div.line-charge-autre').find('span.input-group-btn:not(.ms) button').prop('disabled', true);
        } else {
            $('div.line-charge-autre').find('span.input-group-btn:not(.ms) button').prop('disabled', false);
        }
    }

    $("body").on('click', '#delete-line-charge', function (e) {
        e.preventDefault();
        $(this).closest('div.line-charge-autre').remove();
        makeFirstUndeleted();
    });

    $("body").on('change', 'select.mode-autre', function () {
        if ($(this).find('option:selected').text() === 'Mode Paiement')
            $(this).closest('.line-charge-autre').find('input.isPaid').removeAttr('name');
        else {
            var name = $(this).closest('.line-charge-autre').find('input.isPaid').attr('data-name');
            $(this).closest('.line-charge-autre').find('input.isPaid').attr('name', name);
        }
        makeOtherFormRequired();
    });

    $("body").on('blur keyup', '.ht,.tva', function () {
        $line = $(this).closest('div.line-charge-autre');
        var tva = isNaN(parseFloat($line.find('input.tva').val())) ? 0 : parseFloat($line.find('input.tva').val());
        var ht = isNaN(parseFloat($line.find('input.ht').val())) ? 0 : parseFloat($line.find('input.ht').val());
        var ttc = ht + (ht * tva / 100);
        $line.find('.ttc').val(ttc.toFixed(2));
    });
    $("body").on('blur keyup', '.ttc,.tva', function () {
        $line = $(this).closest('div.line-charge-autre');
        var tva = isNaN(parseFloat($line.find('input.tva').val())) ? 0 : parseFloat($line.find('input.tva').val());
        var ttc = isNaN(parseFloat($line.find('input.ttc').val())) ? 0 : parseFloat($line.find('input.ttc').val());
        var ht = ttc * (100 / (100 + tva));
        $line.find('.ht').val(ht.toFixed(2));
    });

    $("body").on('click', '.edit-charge', function () {
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function (json) {
                if (json.code === 1) {
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
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    $("body").on('change', '#appbundle_charge_modePayment', function () {
        if ($(this).find('option:selected').text() === 'Mode paiement')
            $(this).closest('form').find('input.isPaid').removeAttr('name');
        else {
            var name = $(this).closest('form').find('input.isPaid').attr('data-name');
            $(this).closest('form').find('input.isPaid').attr('name', name);
        }
    });

    $("body").on('click', 'div#large-modal #submit', function () {
        $("#appbundle_charge_edit").trigger('submit');
    });

    $("body").on('submit', '#appbundle_charge_edit', function (e) {
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        var donnes = new FormData();
        $this.find('input,select,textarea').each(function () {
            if ($(this).is('select[multiple]')) {
                $select = $(this);
                $(this).find('option:selected').each(function (index) {
                    donnes.append($select.attr('name') + '[]', $(this).val());
                });
            } else if ($(this).is('input:file') && $(this)[0].files.length > 0)
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
            success: function (json) {
                if (json.code === 1) {
                    $("#large-modal").modal("hide");
                    swal("Succès", json.msg, "success");
                    $("#table-charges").DataTable().ajax.reload();
                    $("#nouveau-tab").removeClass('active in');
                    $("#liste-tab").addClass('active in');
                } else if (json.code === 0) {
                    $("#form-error").html(json.msg.replace(/ERROR:/g, '<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function () {
                swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
                $btn.button('reset');
            }
        });
    });

    $("body").on('click', '.bind_img_edit', function (e) {
        e.preventDefault();
        $(this).closest('.form-group').find('input:file').trigger('click');
    });

    $("body").on('change', '#appbundle_charge_edit input:file', function () {
        if (this.files.length > 0)
            $(this).closest('.form-group').find('a.pic-show').attr('href', URL.createObjectURL(this.files[0]));
        else
            $(this).closest('.form-group').find('a.pic-show').attr('href', $(this).closest('.form-group').find('a.pic-show').attr('data-href'));
        $.Admin.photobox.initialise();
    });
    $('#table-charges').on('draw.dt', function () {
        $.Admin.photobox.initialise();
        $.Admin.bootstrap.toolTip();
    });

    $("body").on('click', '.liste-charges', function (e) {
        e.preventDefault();
        $("#nouveau-tab").removeClass('active in');
        $("#liste-tab").addClass('active in');
    });

    $("body").on('blur keyup', '.ht-edit,.tva-edit', function () {
        $form = $(this).closest('form');
        if ($form.find('input.tva-edit').val() !== '' && $form.find('input.ht-edit').val() !== '') {
            var tva = parseInt($form.find('input.ht-edit').val()) * parseInt($form.find('input.tva-edit').val()) / 100;
            var ttc = parseInt($form.find('input.ht-edit').val()) + tva;
            $form.find('.ttc-edit').val(ttc.toFixed(2));
        } else
            $form.find('.ttc-edit').val('');
    });

    $('body').on('change', 'select.fournisseur', function () {
        $this = $(this);
        $select = $this.closest('.row').find('select[title=Designation]');
        $select.empty();
        $data = {};
        if ($this.val() !== '')
            $data['fournisseur_id'] = $this.val();
        $.post(
            $this.attr('data-href'),
            $data,
            function (json) {
                if (json.code === 1) {
                    $.each(json.types, function (index, item) {
                        if (item.childrens.length === 0) {
                            var option = new Option(item.label, item.id);
                            $select.append(option);
                        } else {
                            var optGroup = '<optgroup label="' + item.label + '">';
                            $.each(item.childrens, function (index, child) {
                                optGroup += '<option value="' + child.id + '">' + child.label + '</option>';
                            });
                            optGroup += '</optgroup>';
                            $select.append(optGroup);
                        }
                    });
                    $.Admin.select.activate();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    $('body').on('change', '#appbundle_charge_fournisseur', function () {
        $this = $(this);
        $select = $("#appbundle_charge_typesDepenses");
        $select.empty();
        $data = {};
        if ($this.val() !== '')
            $data['fournisseur_id'] = $this.val();
        $.post(
            $this.attr('data-href'),
            $data,
            function (json) {
                if (json.code === 1) {
                    $.each(json.types, function (index, item) {
                        if (item.childrens.length === 0) {
                            var option = new Option(item.label, item.id);
                            $select.append(option);
                        } else {
                            var optGroup = '<optgroup label="' + item.label + '">';
                            $.each(item.childrens, function (index, child) {
                                optGroup += '<option value="' + child.id + '">' + child.label + '</option>';
                            });
                            optGroup += '</optgroup>';
                            $select.append(optGroup);
                        }
                    });
                    $.Admin.select.activate();
                }
            },
            "JSON"
        ).fail(function ($xhr) {
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });


    // Gestion fournisseurs
    var $ligneFournisseur;

    $("body").on('click', '.add-fournisseur', function () {
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function (json) {
                if (json.code === 1) {
                    $ligneFournisseur = $this.closest('div.line-charge-autre');
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
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    $("body").on('click', 'div#large-modal #submit', function () {
        if ($("#appbundle_fournisseur").length)
            $("#appbundle_fournisseur").trigger('submit');
    });

    $("body").on('submit', '#appbundle_fournisseur', function (e) {
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function (json) {
                if (json.code === 1) {
                    $("#large-modal").modal("hide");
                    swal("Succès", json.msg, "success");
                    var fournisseur = $.parseJSON(json.fournisseur);
                    var option = new Option(fournisseur.nom, fournisseur.id);
                    $("select.fournisseur").append(option);
                    $.Admin.select.refresh();
                    $ligneFournisseur.find('select.fournisseur').selectpicker('val', fournisseur.id);
                    $ligneFournisseur.find('select.fournisseur').trigger('change');
                } else if (json.code === 0) {
                    $this.closest('div.modal').find('p.form-error').html(json.msg.replace(/ERROR:/g, '<br/>&diams;'));
                }
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    // End fournisseurs


    // gestion des designations
    $("body").on('click', '.add-type', function () {
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('data-href'),
            {},
            function (json) {
                if (json.code === 1) {
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.select.activate();
                    $.Admin.controle.saisie();
                    $("#appbundle_typedepense_fournisseur").parsley();
                    $("#small-modal").modal('show');
                }
                $this.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $this.button('reset');
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    $("body").on('click', 'div#small-modal #submit', function () {
        if ($("#appbundle_typedepense_fournisseur").length)
            $("#appbundle_typedepense_fournisseur").trigger('submit');
    });

    $("body").on('submit', '#appbundle_typedepense_fournisseur', function (e) {
        e.preventDefault();
        $this = $(this);
        $btn = $this.closest('div.modal').find('button#submit');
        $btn.button('loading');
        $.post(
            $this.attr('action'),
            $this.serialize(),
            function (json) {
                if (json.code === 1) {
                    $("#small-modal").modal("hide");
                    swal("Succès", json.msg, "success");
                    $('select.fournisseur').trigger('change');
                } else if (json.code === 0) {
                    $this.closest('div.modal').find('p.form-error').html(json.msg.replace(/ERROR:/g, '<br/>&diams;'));
                }
                $btn.button('reset');
            },
            "JSON"
        ).fail(function ($xhr) {
            $btn.button('reset');
            swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
        });
    });

    // end designation

    autoRun();
    function autoRun() {
        if ($("input:hidden.types").length) {
            console.log('autorun');
            $this = $("#add-charge");
            $data = {};
            $("input:hidden.types").each(function (index) {
                $data[index] = $(this).val();
            });
            $.get(
                $this.attr('data-href'),
                {'types': $data},
                function (json) {
                    if (json.code === 1) {
                        $("#nouveau-tab").find('div.x_content').html(json.form);
                        $("#nouveau-tab").find('#h2-title').html($this.attr('data-title'));
                        $.Admin.select.activate();
                        $.Admin.controle.saisie();
                        $.Admin.dateInput.initialise();
                        $.Admin.checkInput.initialise();
                        autosize($('textarea.auto-growth'));
                        $("#appbundle_charge").parsley();
                        if ($("div.line-charge-autre").length > 0)
                            makeOtherFormRequired();
                        makeFirstUndeleted();
                        $("#liste-tab").removeClass('active in');
                        $("#nouveau-tab").addClass('active in');
                    }
                },
                "JSON"
            ).fail(function ($xhr) {
                swal($xhr.responseJSON.status_text, $xhr.responseJSON.exception, "error");
            });
        }
    }


});