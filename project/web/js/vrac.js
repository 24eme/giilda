/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



var initAutoComplete = function() {
    $('.autocomplete').combobox();
}

var initConditions = function()
{
    if($('#vrac_condition #type_contrat input:checked').length == 0)
        $('#vrac_condition #type_contrat input[value="spot"]').attr('checked','checked');
    if($('#vrac_condition #prix_isVariable input:checked').length == 0)
        $('#vrac_condition #prix_isVariable input[value="0"]').attr('checked','checked');
    updatePanelsConditions();
    $('#vrac_condition #type_contrat input').click(updatePanelsConditions);
    $('#vrac_condition #prix_isVariable input').click(updatePanelsConditions);
}


var updatePanelsConditions = function()
{
    if($('#vrac_condition #type_contrat input:checked').val()=='spot')
    {
        $('section#prix_isVariable').hide();
    }
    else
    {
        $('section#prix_isVariable').show();
        if($('#vrac_condition #prix_isVariable input:checked').val()=='0')
        {
            $('section#prix_variable').hide();
        }
        else
        {
            $('section#prix_variable').show();

        }
    }
}

var initMarche = function()
{
    if($('#vrac_marche #original input:checked').length == 0)
        $('#vrac_marche #original input[value="1"]').attr('checked','checked');
    if($('#vrac_marche #type_transaction input:checked').length == 0)
        $('#vrac_marche #type_transaction input[value="vin_vrac"]').attr('checked','checked');       
   updatePanelsAndUnitLabels();    
   $('#vrac_marche #type_transaction input').click(updatePanelsAndUnitLabels);
}


var updatePanelsAndUnitLabels = function()
{
     switch ($('#vrac_marche #type_transaction input:checked').attr('value'))
    {
         case 'raisins' :
        {
            updatePanelsAndUnitForRaisins();
            $('#vrac_raisin_quantite').keyup(updatePanelsAndUnitForRaisins);
            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForRaisins);
            $('#vrac_raisin_quantite').click(updatePanelsAndUnitForRaisins);
            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForRaisins);
            break;
        }
        case 'mouts' :
        {
            updatePanelsAndUnitForJuice();
            $('#vrac_jus_quantite').keyup(updatePanelsAndUnitForJuice);
            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForJuice);
            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForJuice);
            $('#vrac_jus_quantite').click(updatePanelsAndUnitForJuice);
            break;
        }
        case 'vin_vrac' :
        {
            updatePanelsAndUnitForJuice();
            $('#vrac_jus_quantite').keyup(updatePanelsAndUnitForJuice);
            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForJuice); 
            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForJuice);   
            $('#vrac_jus_quantite').click(updatePanelsAndUnitForJuice);         
            break;
        }
        case 'vin_bouteille' :
        {
            updatePanelsAndUnitForBottle();
            $('#vrac_bouteilles_quantite').keyup(updatePanelsAndUnitForBottle);            
            $('#vrac_prix_unitaire').keyup(updatePanelsAndUnitForBottle);
            $('#vrac_bouteilles_quantite').click(updatePanelsAndUnitForBottle);            
            $('#vrac_prix_unitaire').click(updatePanelsAndUnitForBottle);
            $('#vrac_bouteilles_contenance').change(updatePanelsAndUnitForBottle);
            break;
        }
    }
    
    if($('#type input:checked').length == 0)
        $('#type input[value="domaine"]').attr('checked','checked');   
    
    if($('#type input[value="generique"]:checked')) $('#domaine').hide();
    
    $('#type input').click(function()
    {
        if($(this).val()=='generique') $('#domaine').hide();
        else  $('#domaine').show();       
    });
    
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
    $('section#has_mandataire input').attr('checked', 'checked');    
    $('#vrac_mandatant_vendeur').attr('checked','checked');
    
    majAutocompleteInteractions('vendeur');
    majAutocompleteInteractions('acheteur');
    majAutocompleteInteractions('mandataire');
    majMandatairePanel();
    
    
}

var majAutocompleteInteractions = function(type)
{
    $('#'+type+'_choice div input').live( "autocompleteselect", function(event, ui)
    {
        $('#'+type+'_modification_btn').removeAttr('disabled');
        $('#'+type+'_modification_btn').css('cursor','pointer');        
    });
}

var majModificationsButton = function(type)
{
    if($('section#'+type+'_choice input.ui-autocomplete-input').val()=="") $('#'+type+'_modification_btn').attr('disable','disable');
    else $('#'+type+'_modification_btn').removeAttr('disable');
}


var majMandatairePanel = function()
{
    if($('section#has_mandataire input').attr('checked')) {$('section#mandataire').show();}
    else{$('section#mandataire').hide();}
    
    $('section#has_mandataire input').click(function()
    {
        if($(this).attr('checked'))
        {
            $('section#mandataire').show();
            $('#vrac_mandatant_vendeur').attr('checked','checked');            
        }
        else
        {
            $('section#mandataire').hide();
            $('section#mandataire input').each(function()
            {
                
                if($(this).attr('type')=='checkbox') $(this).attr('checked',false);
                else 
                {
                    if($(this).attr('type')!='button') $(this).val('');
                }
            });
        }
    });
    
    $('#vrac_mandatant_vendeur').click(function()
    {
        if(($('#mandatant input:checked').length == 0) && ($('#vrac_mandatant_vendeur:checked'))) $('#vrac_mandatant_vendeur').attr('checked','checked');
    });
    $('#vrac_mandatant_acheteur').change(function()
    {
        if(($('#mandatant input:checked').length == 0) && ($('#vrac_mandatant_acheteur:checked'))) $('#vrac_mandatant_acheteur').attr('checked','checked');
    });
    
}

var init_ajax_modification = function(type)
{
    $('a#'+type+'_modification_btn').html('Valider');
    $('a#'+type+'_modification_btn').removeClass('btn_orange').addClass('btn_vert');
    $('a#'+type+'_modification_btn').css('cursor','pointer');
    
    $("#"+type+"_choice input").attr('disabled','disabled');
    $("#"+type+"_choice button").attr('disabled','disabled');
    $('div.btnValidation button').attr('disabled','disabled');
    var params = {id : $("#vrac_"+type+"_identifiant").val(), 'div' : '#'+type+'_informations'};  
    ajaxifyPost('modification?id='+$("#vrac_"+type+"_identifiant").val(),params,'#'+type+'_modification_btn','#'+type+'_informations');
}


var init_informations = function(type)
{
    $("#"+type+"_choice input").removeAttr('disabled');
    $("#"+type+"_choice button").removeAttr('disabled');
    
    $("a#"+type+"_modification_btn").html("Modifier");
    $("a#"+type+"_modification_btn").removeClass('btn_vert').addClass('btn_orange');
    
    
    $("#"+type+"_modification_btn").unbind();
    $('div.btnValidation button').removeAttr('disabled');
}
    

$(document).ready(function()
{
     initMarche();
     initConditions();
     initAutoComplete();
});
