/**
 * Fichier : sv12.js
 * Description : fonctions JS spécifiques à SV 12 
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
$(document).ready(function()
{
	$("a#sv12_popup_warning_trigger").fancybox();
	$("a#sv12_popup_warning_trigger").trigger('click');
	
    $('.num_light').change(function() {
	$(this).parent().parent().removeClass('nonsaisi');
	if ($(this).val()*1) {
	    $(this).parent().parent().addClass('saisi');
	}else{
	    $(this).parent().parent().removeClass('saisi');
	}
    });


    /*$('#champ_volumes_vides').change($.fn.updateSaisi);

    if($('#recherche_sv12').exists())
    {
	$('#recherche_sv12 .autocompletion_tags').rechercheTableParTags();
    }*/

});