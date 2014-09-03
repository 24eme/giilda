/**
 * Fichier : global.js
 * Description : fonctions JS génériques
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 **
 ****************************************/

/* =================================================================================== */
/* VARIABLES GLOBALES */
/* =================================================================================== */

// Ancre
var ancreSite = window.location.hash;

/* Utilisateur */
var utilisateur =
{
	terminal: '',
	largeurEcran: ''
};

var objWindow = 
{
	elem: $(window),
	largeur: $(window).width(),
	hauteur: $(window).height()
};

// Fancybox - Config par défaut
var fbConfig =
{
	padding	: 0,
	autoSize : true,
	fitToView : true,
	helpers :
	{
		overlay : { opacity : 1 }
	}
};


/**
 * Initialisation
 ******************************************/
(function($)
{	
	
	/**
	 * Initialise les informations de l'utilisateur
	 * $.initUtilisateur();
	 ******************************************/
	$.initUtilisateur = function()
	{
		$.detectTerminal();
	};
	
	/**
	 * Détecte le nom du terminal utilisé
	 * $.detectTerminal();
	 ******************************************/
	$.detectTerminal = function()
	{
		var terminalAgent = navigator.userAgent.toLowerCase();
		var agentID = terminalAgent.match(/(iphone|ipod|ipad|android)/);
		var version;
		
		if(agentID)
		{
			if(agentID.indexOf('iphone') >= 0) utilisateur.terminal = 'iphone';
			else if(agentID.indexOf('ipod') >=0 ) utilisateur.terminal = 'ipod';
			else if(agentID.indexOf('ipad') >= 0) utilisateur.terminal = 'ipad';
			else if(agentID.indexOf('android') >= 0) utilisateur.terminal = 'android';
		}
		else
		{
			version = parseInt($.browser.version);
			
			if($.browser.webkit) utilisateur.terminal = 'webkit';
			else if($.browser.mozilla) utilisateur.terminal = 'mozilla';
			else if($.browser.opera) utilisateur.terminal = 'opera';
			else if($.browser.msie)
			{
				if(version == 6) utilisateur.terminal = 'msie6';
				else if(version == 7) utilisateur.terminal = 'msie7';
				else if(version == 8) utilisateur.terminal = 'msie8';
				else if(version == 9) utilisateur.terminal = 'msie9';
			}
		}
		
		$('body').addClass(utilisateur.terminal);
		return utilisateur.terminal;
	};
	
	
	/**
	 * Sélection de lignes de tableau
	 * $.initTableSelection();
	 ******************************************/
	$.initTableSelection = function()
	{
		var tables = $('.table_selection');
		
		tables.each(function()
		{
			var table = $(this);
			var selecteurGlobal = table.find('thead .selecteur input');
			var selecteursLignes = table.find('tbody .selecteur input');
			
			// Selection / Déselection globale
			selecteurGlobal.click(function()
			{
				if(selecteurGlobal.is(':checked'))
				{
					selecteursLignes.attr('checked', 'checked');
				}
				else
				{
					selecteursLignes.removeAttr('checked');
				}
			});
			
			// Déselection unique
			selecteursLignes.click(function()
			{
				var selecteur = $(this);
				
				if(!selecteur.is(':checked'))
				{
					selecteurGlobal.removeAttr('checked');
				}
			});
		});
	};

	/**
	 * Ouvre / Ferme la colonne de droite
	 * $.initToggleColonne();
	 ******************************************/
	$.initToggleColonne = function()
	{
		var colonne = $('#colonne');
		var colonneElem = colonne[0];
		var btnColonne = $('#btn_colonne');
		var btnColonneMobile = colonne.find('#btn_colonne_mobile');
		var touchesInAction = {};

		// Tablette
		btnColonne.click(function(e)
		{
			e.preventDefault();
			e.stopPropagation();

			colonne.slideToggle(400, function()
			{
				colonne.toggleClass('ouvert');
				btnColonne.toggleClass('ouvert');	
			});
		});

		// Mobile
		btnColonneMobile.click(function(e)
		{
			e.preventDefault();
			e.stopPropagation();

			colonne.toggleClass('ouvert');
		});

		colonne.click(function(e)
		{
			e.stopPropagation();
		});

		// Fermeture de la colonne au swipe droit
		colonneElem.addEventListener('touchstart', function(e)
		{
			var touches = e.changedTouches;

		    for(var j = 0; j < touches.length; j++) 
		    {
		         /* store touch info on touchstart */
		         touchesInAction["$" + touches[j].identifier] = 
		         {
		            identifier : touches[j].identifier,
		            pageX : touches[j].pageX,
		            pageY : touches[j].pageY
		         };
		    }
		}, false);

		colonneElem.addEventListener('touchend', function(e)
		{
			var touches = e.changedTouches;

			for(var j = 0; j < touches.length; j++) 
			{
		        /* access stored touch info on touchend */
		        var theTouchInfo = touchesInAction[ "$" + touches[j].identifier ];
		        theTouchInfo.dx = touches[j].pageX - theTouchInfo.pageX;  /* distance en x depuis touchstart */
		        theTouchInfo.dy = touches[j].pageY - theTouchInfo.pageY;  /* distance en y depuis touchstart */
    		}

    		if(theTouchInfo.dx > 75 && colonne.hasClass('ouvert'))
    		{
    			colonne.removeClass('ouvert');
    		}
		}, false);

		// Fermeture de la colonne lorsqu'on clique en dehors
		$(document).click(function()
		{
			colonne.removeClass('ouvert');
			btnColonne.removeClass('ouvert');
			btnColonneMobile.removeClass('ouvert');
		});
	};

	/**
	 * Ouvre / Ferme le menu sur mobile
	 * $.initToggleNavMobile();
	 ******************************************/
	$.initToggleNavMobile = function()
	{
		var nav = $('#navigation');
		var btnMenu = $('#header .btn_menu');

		btnMenu.click(function(e)
		{
			e.stopPropagation();
			nav.toggleClass('visible_tab');
		});

		$(document).click(function()
		{
			nav.removeClass('visible_tab');
		});
	};
	
	
	
	/* =================================================================================== */
	/* INITIALISATION */
	/* =================================================================================== */
	$(document).ready( function()
	{
		$.initUtilisateur();
		
		//$.metadata.setType('html5');
		$.inputPlaceholder();
		
		$.initTableSelection();

		$.initToggleColonne();

		$.initToggleNavMobile();
	
                if(!('contains' in String.prototype)) {
                   String.prototype.contains = function(str, startIndex) {
                    return -1 !== String.prototype.indexOf.call(this, str, startIndex);
               };
             }
            
	});
	
})(jQuery);