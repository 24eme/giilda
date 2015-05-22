/**
 * Initialisation
 ******************************************/
$(document).ready(function()
{
	if($('.hamza_style .autocompletion_tags').exists()) {
		$('.hamza_style .autocompletion_tags').rechercheTableParTags();
	}
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
		tagsInput : $(),
        nbTagsAjoutes : 0,
        idSelectionnes : [],
        nbResultats : 0,
		permissif: bloc.hasClass('permissif')
    };
    
    // Création des tags
    $.creerListeTags(objRecherche);
	
    $.ajouterTagsParUrl(objRecherche, document.location.href);

    $('a.lien_hamza_style').click(function(e) {
        $.ajouterTagsParUrl(objRecherche, $(this).attr('href'));
        $(document).scrollTo($(this).attr('data-scrollto'));
        return true;
    });
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
        allowNewTags: objRecherche.permissif,
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
	objRecherche.tagsInput = objRecherche.listeTags.find('.tagit-input');
	
	// Si on autorise pas les nouveaux tags
	if(!objRecherche.permissif)
	{
		objRecherche.tagsInput.keydown(function(e)
		{
			// On ajoute le tag grâce à la barre d'espace
			if(e.keyCode == 32)
			{
				$('.ui-autocomplete .ui-state-hover').click();
				return false;
			}
		});
	}
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
};

$.ajouterTagsParUrl = function(objRecherche, url) {
    var url_parser = $.url(url);
    var filtre = url_parser.fparam('filtre');

    return $.ajouterTagsParChaine(objRecherche, filtre);
}

$.ajouterTagsParChaine = function(objRecherche, chaine) {
    objRecherche.listeTags.tagit("reset");

    for(key_tag in objRecherche.tagsSource) {
        var tag = objRecherche.tagsSource[key_tag];
        try {
            if(chaine.match(new RegExp('^'+tag+'$', "i"))) {
               var chaine = chaine.replace(tag, '', 'g');
               objRecherche.listeTags.tagit("add", {label: tag, value: tag});
            }
        } catch (err) {

        }
    }
    
    return true;
}