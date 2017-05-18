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
    showAnim: 'blind',
    duration: 300,
    showMonthAfterYear: false,
    yearSuffix: ''};

$.datepicker.setDefaults($.datepicker.regional['fr']);

// Désactivation de jours
function disableDays(date) {

    var d = date.getDay();
    var curDate = date.getDate();
    var m = date.getMonth();
    var hour   = ('0'+now.getHours()).slice(-2);

    //Suppression des dimanches et mardis
    if (d == 0  || d == 2)
    {
        return [false] ;
    }
    //Suppression du 1er mai et du 1er novembre
    else if (curDate == 1 && (m == 4 || m == 10))
    {
        return [false];
    }
    // Suppression du 25 décembre
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
var limitHour = now.getHours() >= 18;
var myDate = day + "/" + month + "/" + year;
var halfDay = $(".ticketType option[value='0']");
var allDay = $(".ticketType option[value='1']");


$(function()
{

    $(".datepicker").datepicker(
    {

        minDate: limitHour ? 1 : 0,
        beforeShowDay: disableDays,




        // ON RECUP LA DATE CHOISIE POUR ENLEVER LA JOUR. COMPL. SI = JOUR MEME et 14h +
        onSelect: function(dateText, inst)
        {
            $('#date').val(dateText);

            if (dateText == myDate && hour >= 14 && hour < 24)
            {
                allDay.detach();

            }
            else if (dateText !== myDate && hour >= 14 && hour < 24)
            {
                allDay.insertBefore(halfDay);
            }
        }
    });

});




