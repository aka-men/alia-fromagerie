/**
 * Created by abdelhak on 27/04/2017.
 */

// Validation errors messages for Parsley
// Load this after Parsley

Parsley.addMessages('fr', {
    defaultMessage: "Cette valeur semble non valide.",
    type: {
        email:        "Cette valeur n'est pas une adresse email valide.",
        url:          "Cette valeur n'est pas une URL valide.",
        number:       "Cette valeur doit être un nombre.",
        integer:      "Cette valeur doit être un entier.",
        digits:       "Cette valeur doit être numérique.",
        alphanum:     "Cette valeur doit être alphanumérique."
    },
    notblank:       "Cette valeur ne peut pas être vide.",
    required:       "Ce champ est requis.",
    pattern:        "Cette valeur semble non valide.",
    min:            "Cette valeur ne doit pas être inférieure à %s.",
    max:            "Cette valeur ne doit pas excéder %s.",
    range:          "Cette valeur doit être comprise entre %s et %s.",
    minlength:      "Cette chaîne est trop courte. Elle doit avoir au minimum %s caractères.",
    maxlength:      "Cette chaîne est trop longue. Elle doit avoir au maximum %s caractères.",
    length:         "Cette valeur doit contenir entre %s et %s caractères.",
    mincheck:       "Vous devez sélectionner au moins %s choix.",
    maxcheck:       "Vous devez sélectionner %s choix maximum.",
    check:          "Vous devez sélectionner entre %s et %s choix.",
    equalto:        "Cette valeur devrait être identique."
});

Parsley.setLocale('fr');

$.Admin = {};

/* Form - Select - Function ================================================================================================
 *  You can manage the 'select' of form elements
 *
 */
$.Admin.select = {
    activate: function () {
        if ($.fn.selectpicker) {
            $('select:not(.ms):not(.msl)').selectpicker('destroy');
            $('select:not(.ms):not(.msl)').selectpicker();
        }
        $("select.msl").multiSelect({
            selectableHeader: "<input type='text' style='height: 30px;margin-bottom: 5px;' class='search-input form-control' autocomplete='off' placeholder='Recherche'>",
            selectionHeader: "<input type='text' style='height: 30px;margin-bottom: 5px;' class='search-input form-control' autocomplete='off' placeholder='Recherche'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    },
    refresh: function(){
        if ($.fn.selectpicker) {
            $('select:not(.ms):not(.msl)').selectpicker('refresh');
        }
        $("select.msl").multiSelect('refresh');
    }
};

$.Admin.controle = {
    saisie: function(){
        /*
         * la classe phone autorise la saisie des chifres, des espace et le signe +
         * exemple : +212 522 21 54 98 | 06 67 35 48 67
         */
        $(".phone").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if ((char < 48 || char > 57) && char != 43 && char != 32) {
                return false;
            }
        });

        /*
         * la classe compteBancaire autorise la saisie des chifres et des espace
         * exemple : 007 780 000000 5405000057 42
         */
        $(".compteBancaire").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if ((char < 48 || char > 57) && char != 32) {
                return false;
            }
        });

        /*
         * la classe numerique autorise la saisie des chifres et des points
         * exemple : 5.25 | 50.09
         */
        $(".numerique").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if ((char < 48 || char > 57) && char != 46) {
                return false;
            }
        });


        /*
         * la classe entier autorise la saisie des chifres seulement
         * exemple : 5 | 152
         */
        $(".entier").on("keypress", function (e) {
            var char = e.keyCode || e.which;
            if (char < 48 || char > 57) {
                return false;
            }
        });

        /*
         * Empecher copier/coller pour toutes les classes
         */
        $('.phone,.numerique,.entier').bind('paste', function (e) {
            e.preventDefault();
        });
    }
};

$.Admin.photobox ={
    initialise: function(){
        $('body').photobox('destroy');
        $('body').photobox('[data-fancybox]',{
            time:0,
            single: true
        });

    }
};
var old_fn = $.datepicker._updateDatepicker;

$.datepicker._updateDatepicker = function(inst) {
    old_fn.call(this, inst);

    var buttonPane = $(this).datepicker("widget").find(".ui-datepicker-buttonpane");

    $("<button type='button' class='ui-datepicker-clean ui-state-default ui-priority-primary ui-corner-all'>Effacer</button>").appendTo(buttonPane).click(function(ev) {
        $.datepicker._clearDate(inst.input);
    }) ;
}
$.Admin.dateInput ={
    initialise: function(){
            $('.date-input').datepicker({
                dateFormat: 'dd/mm/yy',
                showButtonPanel: true,
                currentText: "Aujourd'hui",
                closeText: 'Fermer',
                changeMonth: true,
                changeYear: true,
                dayNamesMin: [ "Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa" ],
                monthNamesShort : [ "janv", "Févr", "Mars", "Avr", "Mai", "Juil", "Juil", "Aout", "Sept", "Oct", "Nov", "Déc" ],
                monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Setembre", "Octobre", "Novembre", "Décembre" ]
            });
        }
};

$.Admin.checkInput ={
    initialise: function(){
        $('input:checkbox.flat').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        if ($(".js-switch")[0]) {

            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function (html) {
                var switchery = new Switchery(html, {
                    color: '#26B99A'
                });
            });
        }
    }
};

function getObject(lien,nomObjet){
    $.get(
        lien,
        {},
        function(json){
            if(json.code === 1){
              $objet = $.parseJSON(json[nomObjet]);
            }
        },
        "JSON"
    ).fail(function ($xhr) {
        swal("Une erreur s'est produite dans l'application!");
    });
}


$.Admin.bootstrap = {
    toolTip: function(){
      $('[data-toggle="tooltip"]').tooltip({
          html: true,
          container: 'body'
      });
    }
};

$.Admin.tagsInput = {
  initialise: function(){
      if(typeof $.fn.tagsInput !== 'undefined'){
          $('input.tagsInput').tagsInput({
              width: 'auto',
              height:'auto',
              defaultText:'Infos suplémentaires',
              placeholderColor:'gray',
              removeWithBackspace : true
          });

      }
  }
};

$.Admin.btnTable = {
    style: function(){
        $('.btn-table').closest('td').css({
            'padding-top':'2px',
            'padding-bottom':'2px'
        });
    }
};
/**
 *
 * @param number
 * @param decimals
 * @param decPoint
 * @param thousandsSep
 * @returns {string|*}
 */
function number_format (number, decimals, decPoint, thousandsSep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
    var n = !isFinite(+number) ? 0 : +number
    var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
    var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
    var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
    var s = ''
    var toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec)
        return '' + (Math.round(n * k) / k)
                .toFixed(prec)
    }
    // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || ''
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }
    return s.join(dec)
}

/**
 * Retourner le nombre des chiffres apres la virgule
 * @param number
 * @returns {number}
 */
function nbrDecimal(number,separatorDecimal) {
    var numberString = number.toString();
    var part = numberString.split(separatorDecimal);
    if(part.length === 2){
        return part[1].length;
    }
    return 0;
}

/**
 *
 * @param number
 * @param separatorDecimal
 * @returns {*}
 */
function toFixedAdmin(number,separatorDecimal) {
    var numberString = number.toString();
    var part = numberString.split(separatorDecimal);
    if(part.length === 2 && part[1].length > 2){
        var resultString = part[0]+'.'+ part[1].slice(0,2);
        return parseFloat(resultString);
    }
    return number;
}

$(document).ready(function(){
    $.Admin.select.activate();
    $.Admin.controle.saisie();
    $.Admin.photobox.initialise();
    $.Admin.dateInput.initialise();
    $.Admin.checkInput.initialise();
    $.Admin.tagsInput.initialise();
    $.Admin.btnTable.style();

    $("body").on('click','.b-link',function(){
        window.open($(this).data('href'), '_blank');
    });

});
