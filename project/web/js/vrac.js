/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function()
{
    if(jQuery('#vrac_marche #type_transaction input:checked').length == 0)
        jQuery('#vrac_marche #type_transaction input[value="raisins"]').attr('checked','checked');  
   
    updatePanelsAndUnitLabels();
});


function updatePanelsAndUnitLabels()
{
     switch (jQuery('#vrac_marche #type_transaction input:checked').attr('value'))
    {
         case 'raisins' :
        {
            updatePanelsAndUnitForRaisins();
            break;
        }
            case 'mouts' :
        {
            updatePanelsAndUnitForJuice();
            break;
        }
            case 'vin_vrac' :
        {
            updatePanelsAndUnitForJuice();
            break;
        }
            case 'vin_bouteille' :
        {
            updatePanelsAndUnitForBottle();
            break;
        }
    }
    
}

jQuery('#vrac_marche #type_transaction input').click(function()
{
    updatePanelsAndUnitLabels();
});


function updatePanelsAndUnitForRaisins()
{
    jQuery('section.bouteilles_contenance').hide();
    jQuery('section div.bouteilles_quantite').hide();
    jQuery('section div.jus_quantite').hide();    
    jQuery('section div.raisin_quantite').show();
    
    
}

function updatePanelsAndUnitForJuice()
{
    jQuery('section.bouteilles_contenance').hide();
    jQuery('section div.bouteilles_quantite').hide();    
    jQuery('section div.raisin_quantite').hide();
    
    jQuery('section div.jus_quantite').show();
    
}

function updatePanelsAndUnitForBottle()
{     
    jQuery('section div.raisin_quantite').hide();
    jQuery('section div.jus_quantite').hide();
    
    jQuery('section.bouteilles_contenance').show();
    jQuery('section div.bouteilles_quantite').show();
    
    alert(jQuery('section div.bouteilles_quantite'));
    
    alert(jQuery('section div.bouteilles_quantite'));
}
