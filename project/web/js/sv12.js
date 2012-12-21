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


    $('#champ_volumes_vides').change($.fn.updateSaisi);

    if($('#recherche_sv12').exists())
    {
	$('#recherche_sv12 .autocompletion_tags').rechercheTableParTags();
    }

    $.fn.updateSaisi();

});



/**
 * Initialise la recherche dans un tableau
 * HTML par tags
 * $(s).rechercheTableParTags();
 ******************************************/
$.fn.rechercheTableParTags = function()
{
	var bloc = $(this);
	var table = $(bloc.attr('data-table'));
	var tableLignes = table.find('tbody tr');
	var tableLigneVide = tableLignes.filter('.vide');
	
	var objRecherche =
	{
		bloc : bloc,
		source : eval(bloc.attr('data-source')),
		table : table,
		tableLignes : tableLignes,
		tableLigneVide : tableLigneVide,
		listeTags : bloc.find('.tags'),
		tabTags : [],
		tagsSource : [],
		tagsAjoutes : $(),
		nbTagsAjoutes : 0,
		idSelectionnes : [],
		nbResultats : 0
	};
	
	// Création des tags
	$.creerListeTags(objRecherche);
};

/**
 * Créé la liste des tags pour l'autocomplétion
 * $.creerListeTags(objRecherche);
 ******************************************/
$.creerListeTags = function(objRecherche)
{
	
	// Récupération de tous les tags avec les doublons
	$.each(objRecherche.source, function(id, tagsAssocies)
	{
		$.merge(objRecherche.tabTags, tagsAssocies);
	});
	
	// Récupération de tous les tags sans les doublons
	$.each(objRecherche.tabTags, function(i, tag)
	{
		if($.inArray(tag, objRecherche.tagsSource) < 0)
		{
			objRecherche.tagsSource.push(tag);
		}
	});
	
	// Initialisation de l'autocomplétion
	objRecherche.listeTags.tagit
	({
		tagSource: objRecherche.tagsSource,
		singleField: true,
		caseSensitive: false,
		allowNewTags: false,
		select: true,
		
		// Ajout ou suppression d'un tag
		tagsChanged: function(tagValue, action, element)
		{
			if(action == 'added' || action == 'popped')
			{
				$.fn.selectionIdParTags(objRecherche);
			}
		}
	});
	
	objRecherche.tagsAjoutes = objRecherche.listeTags.next('.tagit-hiddenSelect');
};


/**
 * Sélectionne les id en fonction des tags
 * $(s).selectionIdParTags(objRecherche);
 ******************************************/
$.fn.selectionIdParTags = function(objRecherche)
{
	var tagsAjoutes = objRecherche.tagsAjoutes.val();
	
	// Nombre de tags ajoutés
	if(tagsAjoutes) objRecherche.nbTagsAjoutes = tagsAjoutes.length;
	else objRecherche.nbTagsAjoutes = 0;
	
	// Vide le tableau d'Id
	objRecherche.idSelectionnes.length = 0; 
	
	
	// S'il y a des tags à trouver
	if(objRecherche.nbTagsAjoutes > 0)
	{	
		// Parcours de tous les id
		$.each(objRecherche.source, function(id, tabTags)
		{
			var trouve = true;
			var i = 0;
			
			// Parcours des tags ajoutés
			while(trouve && i <= (objRecherche.nbTagsAjoutes-1) )
			{
				// Si le tag courant est absent du tableau de tags de l'id courant
				if($.inArray(tagsAjoutes[i], tabTags) == -1)
					trouve = false;
				
				i++;
			}
			
			// Ajout de l'id
			if(trouve) objRecherche.idSelectionnes.push('#'+id); 
		});
		
		objRecherche.nbResultats = objRecherche.idSelectionnes.length;
	}	
	
	// Pas de tags à trouver
	else
	{
		objRecherche.nbResultats = -1;
	}
	
	// Tri du tableau
	$.triTableParTags(objRecherche);
};


/**
 * Tri la table en fonction des tags
 * $.triTableParTags(objRecherche);
 ******************************************/
$.triTableParTags = function(objRecherche)
{
	var selecteur = '';
	
	// Si aucun tag
	if(objRecherche.nbResultats == -1)
	{
		objRecherche.tableLignes.show();
		objRecherche.tableLigneVide.hide();
	}
	
	// Si pas de résultat, affichage de la ligne vide
	else if(objRecherche.nbResultats == 0)
	{
		objRecherche.tableLignes.hide();
		objRecherche.tableLigneVide.show();
	}
	
	// Sinon, affichage des lignes concernées
	else
	{
		objRecherche.tableLigneVide.hide();
		objRecherche.tableLignes.hide();
	
		$.each(objRecherche.idSelectionnes, function(i, id)
		{
			if(i == 0) selecteur += id;
			else selecteur += ', ' + id;
		});
		
		$(selecteur).show();
	}
    
    $.fn.updateSaisi();
};

$.fn.updateSaisi = function() {
    if ($('#champ_volumes_vides').is(':checked')) {
	$('.saisi').hide();
    }else{
	$('.saisi').show();
    }
}
