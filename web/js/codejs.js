// Rendre le bouton stripe actif après acceptation des CGV

document.getElementsByClassName("stripe-button-el")[0].disabled=true;


$(function() {
    $('#cgv').click(function() {
        if ($('#cgv:checked').length > 0) {
            document.getElementsByClassName("stripe-button-el")[0].disabled=false;

        } else {
            document.getElementsByClassName("stripe-button-el")[0].disabled=true;
        }
    });
});


