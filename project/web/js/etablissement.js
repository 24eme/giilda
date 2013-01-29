$(document).ready(function()
{
    var maj_valider_button = function(){
        if($('input[name="etablissement_modification[statut]"]:checked').val() == "ACTIF"){
            if($('input[name="etablissement_modification[adresse_societe]"]:checked').val() == 1){
                $('.btn_valider').text('Valider');
            }else{
                $('.btn_valider').text("Valider et saisir l'interlocuteur");
            }
        }else{
            $('.btn_valider').text('Valider');
        }
    }
    maj_valider_button();
    
    $('input[name="etablissement_modification[adresse_societe]"]').change(function() {
        maj_valider_button();        
    });
    $('input[name="etablissement_modification[statut]"]').change(function() {
        maj_valider_button();
    });
    

});