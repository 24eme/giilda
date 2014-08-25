

var initSignaturePopup = function() {

    $('a.signature_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
    $('#signature_popup_content a#signature_popup_close').click(function(){
        $.fancybox.close();
    })
    
    $('#signature_popup_content button#signature_popup_confirm').click(function(){
        $("form#vrac_validation").submit();
    });
    
};

var triggerSignaturePopup = function() {
    $('a.signature_popup').click();
}

var initRechercheFiltre = function() {

    $('form#filtres_historique select#campagne').change(function() {
        $('form#filtres_historique').submit();
    });

    $('form#filtres_historique select#etablissement').change(function() {
        $('form#filtres_historique').submit();
    });

    $('form#filtres_historique select#statut').change(function() {
        $('form#filtres_historique').submit();
    });

}

var initTeledeclarationCourtierSoussigne = function() {

    $("#teledeclaration_courtier_interlocuteur_commercial_show").change(function() {
        if($(this).is(":checked")){
            $("#teledeclaration_courtier_interlocuteur_commercial").show();
        }else{
            $("#teledeclaration_courtier_interlocuteur_commercial").hide();
            $("#teledeclaration_courtier_interlocuteur_commercial .ui-autocomplete-input").focus();
            $("#teledeclaration_courtier_interlocuteur_commercial .ui-autocomplete-input").val("");
            $("#teledeclaration_courtier_interlocuteur_commercial .ui-autocomplete-input").blur();
        }
    })

}

$(document).ready(function()
{
    initSignaturePopup();
    initRechercheFiltre();
});

