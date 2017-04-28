$.datepicker.regional['fr'] = {
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
    monthNamesShort: ['Janv.','Févr.','Mars','Avril','Mai','Juin','Juil.','Août','Sept.','Oct.','Nov.','Déc.'],
    dayNames: ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'],
    dayNamesShort: ['Dim.','Lun.','Mar.','Mer.','Jeu.','Ven.','Sam.'],
    dayNamesMin: ['D','L','M','M','J','V','S'],
    weekHeader: 'Sem.',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''};

$.datepicker.setDefaults($.datepicker.regional['fr']);

// Désactivation de certains jours
function disableDays(date) {

    var m = date.getMonth();
    var d = date.getDay();
    var curDate = date.getDate();

    //le mardi
    if (d == 2)
    {
        return [false] ;
    }

    //le 1er novembre et le 1er mai
    else if (curDate == 1 && (m == 10 || m == 4))
    {
        return [false];
    }
    // le 25 décembre
    else if (curDate == 25 && m == 11){
        return [false];
    }
    else
    {
        return [true] ;
    }

}

var now = new Date();
var year   = now.getFullYear();
var month    = ('0'+(now.getMonth() + 1 )).slice(-2);
var day    = ('0'+now.getDate()   ).slice(-2);
var hour   = ('0'+now.getHours()  ).slice(-2);

var today = day + "/" + month + "/" + year;

var ticketOptionDelete = $(".typeTicket option[value='1']");
var formSelect = $(".typeTicket option[value='0']");

var timeCond = now.getHours() >= 18;

$(function() {

    $(".datepicker").datepicker({
        minDate: timeCond ? 1 : 0,
        changeMonth: false,
        changeYear: false,
        beforeShowDay: disableDays,
        // ON RECUP LA DATE CHOISIE POUR ENLEVER LA JOUR. COMPL. SI = JOUR MEME et 14h +
        onSelect: function(dateText, inst){
            $('#date').val(dateText);

            if (dateText == today && hour >= 14 && hour < 24){
                ticketOptionDelete.detach();
            }
            else{
                ticketOptionDelete.insertBefore(formSelect);
            }
        }
    });
});





