/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function()
{
     initMarche();
     initConditions();
     initAutoComplete();
});

var initAutoComplete = function() {
    jQuery('.autocomplete').combobox();
}

var initConditions = function()
{
    if(jQuery('#vrac_condition #type_contrat input:checked').length == 0)
        jQuery('#vrac_condition #type_contrat input[value="standard"]').attr('checked','checked');
    if(jQuery('#vrac_condition #prix_isVariable input:checked').length == 0)
        jQuery('#vrac_condition #prix_isVariable input[value="0"]').attr('checked','checked');
    updatePanelsConditions();
    jQuery('#vrac_condition #type_contrat input').click(updatePanelsConditions);
    jQuery('#vrac_condition #prix_isVariable input').click(updatePanelsConditions);
}


var updatePanelsConditions = function()
{
    if(jQuery('#vrac_condition #type_contrat input:checked').val()=='standard')
    {
        jQuery('section#prix_isVariable').hide();
    }
    else
    {
        jQuery('section#prix_isVariable').show();
        if(jQuery('#vrac_condition #prix_isVariable input:checked').val()=='0')
        {
            jQuery('section#prix_variable').hide();
        }
        else
        {
            jQuery('section#prix_variable').show();

        }
    }
}

var initMarche = function()
{
    if(jQuery('#vrac_marche #type_transaction input:checked').length == 0)
        jQuery('#vrac_marche #type_transaction input[value="vin_vrac"]').attr('checked','checked');  
   updatePanelsAndUnitLabels();    
   jQuery('#vrac_marche #type_transaction input').click(updatePanelsAndUnitLabels);
}


var updatePanelsAndUnitLabels = function()
{
     switch (jQuery('#vrac_marche #type_transaction input:checked').attr('value'))
    {
         case 'raisins' :
        {
            updatePanelsAndUnitForRaisins();
            jQuery('#vrac_raisin_quantite').keyup(updatePanelsAndUnitForRaisins);
            jQuery('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForRaisins);
            jQuery('#vrac_raisin_quantite').click(updatePanelsAndUnitForRaisins);
            jQuery('#vrac_prix_unitaire').click(updatePanelsAndUnitForRaisins);
            break;
        }
        case 'mouts' :
        {
            updatePanelsAndUnitForJuice();
            jQuery('#vrac_jus_quantite').keyup(updatePanelsAndUnitForJuice);
            jQuery('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForJuice);
            jQuery('#vrac_prix_unitaire').click(updatePanelsAndUnitForJuice);
            jQuery('#vrac_jus_quantite').click(updatePanelsAndUnitForJuice);
            break;
        }
        case 'vin_vrac' :
        {
            updatePanelsAndUnitForJuice();
            jQuery('#vrac_jus_quantite').keyup(updatePanelsAndUnitForJuice);
            jQuery('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForJuice); 
            jQuery('#vrac_prix_unitaire').click(updatePanelsAndUnitForJuice);   
            jQuery('#vrac_jus_quantite').click(updatePanelsAndUnitForJuice);         
            break;
        }
        case 'vin_bouteille' :
        {
            updatePanelsAndUnitForBottle();
            jQuery('#vrac_bouteilles_quantite').keyup(updatePanelsAndUnitForBottle);            
            jQuery('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForBottle);
            jQuery('#vrac_bouteilles_quantite').click(updatePanelsAndUnitForBottle);            
            jQuery('#vrac_prix_unitaire').click(updatePanelsAndUnitForBottle);
            jQuery('#vrac_bouteilles_contenance').change(updatePanelsAndUnitForBottle);
            break;
        }
    }
    
}

var updatePanelsAndUnitForRaisins = function()
{
    jQuery('section.bouteilles_contenance').hide();
    jQuery('section div.bouteilles_quantite').hide();
    jQuery('section div.jus_quantite').hide();    
    jQuery('section div.raisin_quantite').show();
    

    majTotal("raisin_quantite","(en kg)","€/kg");  
}

var updatePanelsAndUnitForJuice = function()
{
    jQuery('section.bouteilles_contenance').hide();
    jQuery('section div.bouteilles_quantite').hide();    
    jQuery('section div.raisin_quantite').hide();    
    jQuery('section div.jus_quantite').show();
    
    majTotal("jus_quantite","(en hl)","€/hl");    
}

var updatePanelsAndUnitForBottle = function()
{     
    jQuery('section div.raisin_quantite').hide();
    jQuery('section div.jus_quantite').hide();
    
    jQuery('section.bouteilles_contenance').show();
    jQuery('section div.bouteilles_quantite').show();
    
    var volume_total = 0.0;
    var bouteilles_quantite = jQuery('#vrac_bouteilles_quantite').val();
    var bouteilles_contenance = jQuery('#vrac_bouteilles_contenance').val();
    if(bouteilles_quantite == "" || bouteilles_contenance == "") return; 
    
    var numeric =  new RegExp("^[0-9]*$","g");
    
    if(numeric.test(bouteilles_quantite))
    {
        volume_total = (parseInt(bouteilles_contenance)/10000) * parseInt(bouteilles_quantite);
        jQuery('div.bouteilles_quantite span#volume_unite_total').text("(soit "+volume_total+" hl)");
        var bouteilles_price = jQuery('#vrac_prix_unitaire').val();
        var bouteilles_priceReg = (new RegExp("^[0-9]*[.][0-9]{2}$","g")).test(bouteilles_price);
        if(bouteilles_priceReg)
        {
           var prix_total = parseInt(bouteilles_quantite) * parseFloat(bouteilles_price);
           jQuery('#vrac_prix_total').text(prix_total);
           var prix_hl = prix_total / volume_total;
           jQuery('section#prixUnitaire span#prix_unitaire_unite').text("€/btlle");
           jQuery('section#prixUnitaire span#prix_unitaire_hl').text("(soit "+prix_hl+" €/hl)");
        }
    }
}

var majTotal = function(quantiteField,unite,prixParUnite){
    var quantite = jQuery('#vrac_'+quantiteField).val();
    var numeric =  new RegExp("^[0-9]*$","g");
    
    if(numeric.test(quantite))
    {
        jQuery('div.'+quantiteField+' span#volume_unite_total').text(unite);
        var prix_unitaire = jQuery('#vrac_prix_unitaire').val();
        var priceReg = (new RegExp("^[0-9]*[.][0-9]{2}$","g")).test(prix_unitaire);
        if(priceReg)
        {
           var prix_total = quantite * parseFloat(prix_unitaire);
           jQuery('#vrac_prix_total').text(prix_total);
           jQuery('section#prixUnitaire span#prix_unitaire_unite').text(prixParUnite);
        }
    }
}


var init_ajax_nouveau = function()
{
    ajaxifyAutocompleteGet('getInfos','#vendeur_choice','#vendeur_informations');
    ajaxifyAutocompleteGet('getInfos','#acheteur_choice','#acheteur_informations'); 
    ajaxifyAutocompleteGet('getInfos','#mandataire_choice','#mandataire_informations');
    $('section#has_mandataire input').attr('checked', 'checked')
    $('#vrac_mandatant_vendeur').attr('checked','checked');
    majMandatairePanel();
}


var majMandatairePanel = function()
{
    if($('section#has_mandataire input').attr('checked')) {$('section#mandataire').show();}
    else{ $('section#mandataire').hide(); }
    
    $('section#has_mandataire input').click(function()
    {
        if($(this).attr('checked')) {$('section#mandataire').show();}
        else
        {
            $('section#mandataire').hide();
            $('section#mandataire input').each(function()
            {
                $(this).val('');
            });
        }
    });
}

var init_ajax_modification = function(type)
{
    $('#'+type+'_modification_btn').val('Enregistrer');
    $("#"+type+"_choice input").attr('disabled','disabled');
    $("#"+type+"_choice button").attr('disabled','disabled');
    var params = {id : $("#vrac_"+type+"_identifiant").val(), 'div' : '#'+type+'_informations'};    
    ajaxifyPost('modification?id='+$("#vrac_"+type+"_identifiant").val(),params,'#'+type+'_modification_btn','#'+type+'_informations');
}


var init_informations = function(type)
{
    $("#"+type+"_choice input").removeAttr('disabled');
    $("#"+type+"_choice button").removeAttr('disabled');
    $("#"+type+"_modification_btn").val("Modifier");
    $("#"+type+"_modification_btn").unbind();
}
    