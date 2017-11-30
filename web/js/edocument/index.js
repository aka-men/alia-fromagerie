/**
 * Created by abdelhak on 11/07/2017.
 */

$(document).ready(function(){


    $("body").on('click','#bind-img',function(e){
        e.preventDefault();
        $(this).closest('div.input-group').find('input:file').trigger('click');
    });

    $("body").on('submit','#appbundle_edocument',function(e){
        e.preventDefault();
        $this = $(this);
        $btn = $this.find('button[type=submit]');
        $btn.button('loading');
        var donnes = new FormData();
        $this.find('select,input,textarea').each(function(){
            if ($(this).is('input:file') && $(this)[0].files.length > 0)
                donnes.append($(this).attr('name'), $(this)[0].files[0]);
            else if ($(this).is('input:checkbox') && $(this).is(':checked'))
                donnes.append($(this).attr('name'), 1);
            else
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
                    swal("Succès",json.msg, "success");
                    $("#documents-items").html(json.items);
                }else if(json.code === 0){
                    $this.closest('div.modal').find('p.form-error').html(json.msg.replace(/ERROR:/g,'<br/>&diams;'));
                }
                $btn.button('reset');
            },
            error: function ($xhr, status, error) {
                console.log($xhr);
                console.log(status);
                console.log(error);
                swal($xhr.responseJSON.status_text,$xhr.responseJSON.exception,"error");
                $btn.button('reset');
            }
        });
    });

    $("body").on('click','.remove-document',function(e){
        e.preventDefault();
        $this = $(this);
        swal({
                title: "Voullez-vous supprimer ce document ?",
                text: "Si vous supprimez ce document vous ne pouvez pas le récupérer",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "red",
                confirmButtonText: "Oui, supprimer!",
                closeOnConfirm: true
            },
            function(){
                $this.button('loading');
                $.post(
                    $this.attr('href'),
                    {},
                    function(json){
                        if(json.code === 1){
                            swal("Succès",json.msg, "success");
                           $("#documents-items").html(json.items);
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

    $("body").on('click','.edit-document',function(e){
        e.preventDefault();
        $this = $(this);
        $this.button('loading');
        $.get(
            $this.attr('href'),
            {},
            function(json){
                if(json.code === 1){
                    $("#small-modal").find('div.modal-body').html(json.form);
                    $("#small-modal").find('.modal-title').html($this.attr('data-title'));
                    $.Admin.controle.saisie();
                    $("#appbundle_edocument_edit").parsley();
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
        if($("#appbundle_edocument_edit").length)
            $("#appbundle_edocument_edit").trigger('submit');
    });

    $("body").on('submit','#appbundle_edocument_edit',function(e){
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
                    $("#documents-items").html(json.items);
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


});
