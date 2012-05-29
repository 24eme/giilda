/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function()
{
     init();
});

function init()
{
    if(jQuery('#vrac_marche #type_transaction input:checked').length == 0)
        jQuery('#vrac_marche #type_transaction input[value="raisins"]').attr('checked','checked');  
   updatePanelsAndUnitLabels();    
   jQuery('#vrac_marche #type_transaction input').click(updatePanelsAndUnitLabels);
}


function updatePanelsAndUnitLabels()
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

function updatePanelsAndUnitForRaisins()
{
    jQuery('section.bouteilles_contenance').hide();
    jQuery('section div.bouteilles_quantite').hide();
    jQuery('section div.jus_quantite').hide();    
    jQuery('section div.raisin_quantite').show();
    

    majTotal("raisin_quantite","(en kg)","€/kg");  
}

function updatePanelsAndUnitForJuice()
{
    jQuery('section.bouteilles_contenance').hide();
    jQuery('section div.bouteilles_quantite').hide();    
    jQuery('section div.raisin_quantite').hide();    
    jQuery('section div.jus_quantite').show();
    
    majTotal("jus_quantite","(en hl)","€/hl");    
}

function updatePanelsAndUnitForBottle()
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

function majTotal(quantiteField,unite,prixParUnite){
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
