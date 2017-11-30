/**
 * Created by abdelhak on 16/07/2017.
 */


$(document).ready(function(){

    $('.date-month').datetimepicker({
        format: 'MM/YYYY',
        locale: 'fr'
    });

    $('.date').datetimepicker({
        format: 'DD/MM/YYYY',
        locale: 'fr'
    });

    $(".date").on("dp.change", function (e) {
        $(this).parsley().validate();
    });

    $("body").on('change','.check-type-report',function(){
        var id = $(this).val();
       if(id === 'month'){
           $(this).closest('div.x_panel').find('div#periode').hide();
           $(this).closest('div.x_panel').find('div#month').show();
           $(this).closest('div.x_panel').find('input:text.date-month').attr('required','required');
           $(this).closest('div.x_panel').find('input:text.date-month').attr('name', $(this).closest('div.x_panel').find('input:text.date-month').attr('id'));
           $(this).closest('div.x_panel').find('input:text.date-range-max,input:text.date-range-min').removeAttr('required').removeAttr('name');
       }else if(id === 'periode'){
           $(this).closest('div.x_panel').find('div#month').hide();
           $(this).closest('div.x_panel').find('div#periode').show();
           $(this).closest('div.x_panel').find('input:text.date-range-max,input:text.date-range-min').attr('required','required');
           $(this).closest('div.x_panel').find('input:text.date-range-max').attr('name',$(this).closest('div.x_panel').find('input:text.date-range-max').attr('id'));
           $(this).closest('div.x_panel').find('input:text.date-range-min').attr('name',$(this).closest('div.x_panel').find('input:text.date-range-min').attr('id'));
           $(this).closest('div.x_panel').find('input:text.date-month').removeAttr('required').removeAttr('name');
       }
       $("form#benefice").parsley();
    });

    $("body").on('click','.ben-net-pdf',function(e){
       e.preventDefault();
       $form = $(this).closest('form');
        $form.append('<input type="hidden" name="pdf" value="1" id="export-pdf" />');
        $form.trigger('submit');
        window.setTimeout(function () {
            $form.find("#export-pdf").remove();
        },3000);
    });


});