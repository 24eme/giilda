

var initSignaturePopup = function() {

    $('a.signature_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
};

var triggerSignaturePopup = function() {
    $('a.signature_popup').click();
}

var initRechercheFiltre = function() {
    
$('form#filtres_historique select#campagne').change(function(){
    $('form#filtres_historique').submit();
});

$('form#filtres_historique select#etablissement').change(function(){
    $('form#filtres_historique').submit();
});


}


$(document).ready(function()
{
    initSignaturePopup();    
    initRechercheFiltre();
});

