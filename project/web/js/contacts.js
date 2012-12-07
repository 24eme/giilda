/**
 * Fichier : contact.js
 * Description : fonctions JS spécifiques aux contacts
 * Auteur : Mikael Guillin - mguillin[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
(function($)
{
	
	$(document).ready(function()
	{
		$.ajoutChamp();
		$.suppressionChamp();
	});
	
	/**
	 * Ajoute des champs 
	 * $.ajoutChamp();
	 ******************************************/
	
	$.ajoutChamp = function()
	{
		var btnAjout = $('.ajout_champ');
		
		btnAjout.click(function()
		{
			$(this).before('<div class="champ_ajoute"><input type="text" /><button type="button" class="supprime_champ">Supprimer</button></div>');			
			return false;
		});	
	};
	
	/**
	 * Supprime des champs 
	 * $.suppressionChamp();
	 ******************************************/
	
	$.suppressionChamp = function()
	{	
		var btnSuppression = $('.supprime_champ');
		
		btnSuppression.live('click', function()
		{
			$(this).parent().remove();
		});	
	};
	
})(jQuery);

