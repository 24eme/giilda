
/**
 * Fichier : drm.js
 * Description : fonctions JS spécifiques à la drm
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

var objAjoutsLiquidations = {};

/**
 * Initialisation
 ******************************************/
(function($)
{
	var ajoutsLiquidations = $('#ajouts_liquidations');

	$(document).ready( function()
	{
		if(ajoutsLiquidations.exists()) $.initAjoutsLiquidations();
		
		 $('#ajouts_liquidations :checkbox').change(function() {
            $(this).parents('form').submit();
        });
		
        $('.updateProduct').submit(function() {
        	var form = $(this);
        	$.post($(this).attr('action'), $(this).serializeArray(), function (data) {
        	});
        	return false;
        });
		
		if($('#declaratif_info').size()) $.choixCaution();

		$(".flash_temporaire").delay(6000).fadeTo(1000, 0);
	});
	
	/**
	 * Initialise les fonctions de l'étape
	 * Ajouts / Liquidations de la DRM
	 * $.initAjoutsLiquidations();
	 ******************************************/
	$.initAjoutsLiquidations = function()
	{
		var sections = ajoutsLiquidations.find('.tableau_ajouts_liquidations');
		
		$.extend(objAjoutsLiquidations,
		{
			sections: sections,
			tabSections: []
		});
		
		sections.each(function(i)
		{
			var section = $(this);
			var tabInfos = section.getInfosTableauProduits();
			
			objAjoutsLiquidations.tabSections.push(tabInfos);
			
			$.verifCoherenceStock(i);
			$.stylesTableaux(i);
			$.initSupressionProduit(i);
		});
		
	};
	
	/**
	 * Retourne toutes les informations liée
	 * à un tableau de produits
	 * $(s).getInfosTableauProduits();
	 ******************************************/
	$.fn.getInfosTableauProduits = function()
	{
		var tabInfos = {};
		var section = $(this);
		
		var blocRecapProduit = section.find('.recap_produit');
		var tableauRecap = blocRecapProduit.find('.tableau_recap');
		var tableauRecapLignes = tableauRecap.find('tbody tr');
		var tableauRecapChamps = tableauRecap.find('input, select');
		
		tabInfos = 
		{
			section: section,
			blocRecapProduit: blocRecapProduit,
			tableauRecap: tableauRecap,
			tableauRecapLignes: tableauRecapLignes
		};
		
		return tabInfos;
	};
	
	
	/**
	 * Vérifie la cohérence en disponibilité
	 * et stock vide pour les lignes des tableaux
	 * $.verifCoherenceStock();
	 ******************************************/
	$.verifCoherenceStock = function(i)
	{
		var lignes = objAjoutsLiquidations.tabSections[i].tableauRecapLignes;
		
		lignes.each(function()
		{
			var ligne = $(this);
			var disponible = ligne.find('td.disponible input');
			var stockVide = ligne.find('td.stock_vide input');

			if(parseFloat(disponible.val()) > 0) stockVide.attr('disabled', 'disabled');
			else  stockVide.removeAttr('disabled');
		});
	};
	
	
	/**
	 * Styles des tableaux
	 * $.stylesTableaux(i);
	 ******************************************/
	$.stylesTableaux = function(i)
	{
		var tabSection = objAjoutsLiquidations.tabSections[i];
		var lignes = tabSection.tableauRecapLignes.not('.vide');
		var casesTableauRecap = tabSection.tableauRecapLignes.filter(':last').children('td');
		
		// Alternance de couleurs
		lignes.removeClass('alt');
		lignes.filter(':odd').addClass('alt');
	};

	/**
	 * Initialise la suppresion des produits
	 * $.initSupressionProduit(i);
	 ******************************************/
	$.initSupressionProduit = function(i)
	{
		var tabSection = objAjoutsLiquidations.tabSections[i];
		var btnSupprimer = tabSection.tableauRecapLignes.find('.supprimer');
		
		btnSupprimer.click(function()
		{
			var btn = $(this);
			var url = btn.attr('href');
			
                         var confirm = window.confirm('Confirmez-vous la suppression de ce produit ?');
                         
                         if(confirm){
                                $.post(url, function()
                                {
                                        // suppression
                                        btn.parents('tr').remove();

                                        // application des styles
                                        tabSection.tableauRecapLignes = tabSection.tableauRecap.find('tbody tr');
                                        $.stylesTableaux(i);
                                });
                         }
                        
			
			
        	return false;
		});
	};
	
	
	/**
	 * Manipule le champ texte dans l'onglet caution de la page déclaratif
	 * $.choixCaution();
	 ******************************************/
	$.choixCaution = function()
	{
		var conteneurGeneral = $('#principal');
		var conteneurOnglets = $('.contenu_onglet_declaratif').has('#caution_accepte')
		var champCaution = conteneurOnglets.find('#caution_accepte');
		var radioBouton = champCaution.find(':radio');
		var texteCaution = champCaution.find(':text');
		var enclencheursRadio = conteneurOnglets.find('label,:radio');
		
		enclencheursRadio.click(function()
		{
			if(radioBouton.is(':checked')) texteCaution.show();
			else texteCaution.hide();
		}); // fin de click()
	} // fin de $.choixCaution()

})(jQuery);