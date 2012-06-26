/**
 * Fichier : popups.js
 * Description : fonctions JS spécifiques au popups 
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/
(function($)
{
	var objPopups = {}
	
	$.extend(objPopups,
	{
		infosPopups: [],
		/* Configuration par défaut */
		configDefaut:
		{
			autoOpen: false,
			draggable: false,
			resizable: false,
			width: 460,
			modal: true,
			minHeight: 0,
			create: function(event, ui) {},
			open: function(event, ui) {},
			close: function(event, ui) {}
		},

		/* Configuration de form */
		configForm:
		{
			create: function(popup)
			{

			},
			open: function(event, ui)
			{
				$('#' + event.target.id).initPopupForm();
			},
			close: function(event, ui)
			{
				$('#' + event.target.id).uninitPopupForm();
				$(this).trigger('fermer');
			}
		}
	});

		/**
	 * Initialise le formulaire
	 * $(popup).initPopupForm();
	 ******************************************/
	$.fn.initPopupForm = function()
	{
		var popup = $(this);
		var form = popup.find('form');
		var parent = popup.parent();
		var formBtn = form.find('button');
		var btnFermer = popup.find('.btn_fermer');
		var formBtnSubmit = formBtn.filter(':submit');
		
		formBtnSubmit.click(function()
		{
			form.submit();
			return false;
		});
		
		form.find('input:visible', 'select').eq(0).focus();

		// Soumission
		form.live('submit', function()
		{
			//console.log('ok');
			
			popup.addClass('popup_chargement');
			formBtn.attr('disabled', 'disabled');
			
			// Soumission AJAX
			$.post(form.attr('action'), form.serializeArray(), function (data)
			{
				
				// S'il n'y a pas d'erreur -> Redirection
				if(data.success)
				{
					if (form.attr('data-popup-success') == 'popup') {
						$.openPopup(form.attr('data-popup'), 
									form.attr('data-popup-config'), 
									form.attr('data-popup-titre'), 
									data.url, 
									true,
									function() {},
									function() {
										popup.uninitPopupForm();
										popup.remove();
									}
									);
					} else {
						document.location.href = data.url;
					}
				}
				// Sinon remplacement du formulaire par celui récupéré en AJAX
				else
				{
					popup.html(data.content);

					// Réinitialisation des fonctions
					form = popup.find('form');
					formBtn = form.find('button');
					formBtn.removeAttr('disabled');
					formBtnSubmit = formBtn.filter(':submit');
					popup.removeClass('popup_chargement');
					
					formBtnSubmit.click(function()
					{
						form.submit();
						return false;
					});
				}
			}, "json");

            return false;
		});

		btnFermer.live('click', function()
		{
			popup.dialog('close');
			return false;
		});

		// Reinitialisation des champs et 
		// suppression des messages d'erreur à la fermeture
		popup.bind('fermer', function()
		{
			popup.find(':text').val('');
			popup.find('option').removeAttr('selected');
			popup.find(':checkbox,:radio').removeAttr('checked');
			popup.find('.error').remove();
			popup.unbind('fermer');
		});
	};

	$.fn.uninitPopupForm = function () {
		var popup = $(this);
		var form = popup.find('form');
		var btnFermer = popup.find('.btn_fermer');
		form.die('submit');
		btnFermer.die('click');
	}
	

	$(document).ready(function()
	{
		$('.btn_popup').each(function()
		{
			var btnPopup = $(this);
			btnPopup.click(function()
			{
				var reload = btnPopup.attr('data-popup-reload') && btnPopup.attr('data-popup-reload') == "true";				
				$.openPopup(btnPopup.attr('data-popup'), 
							btnPopup.attr('data-popup-config'), 
							btnPopup.text(), 
							btnPopup.attr('href'), 
							reload, 
							function() {
								btnPopup.addClass('btn_chargement');
							}, 
							function() {
								btnPopup.removeClass('btn_chargement');
							});
				
				return false;
			});
		});

		$.initPopupsMsgAide();
	});

	$.openPopup = function(id_popup, config, titre, href, reload, onload, onloaded) 
	{
		if (reload && $(id_popup).length > 0) {
			$(id_popup).dialog('close');
			$(id_popup).remove();
		}

		if (!href) {
			if($(id_popup).exists())
			{
				$.initPopup(id_popup, config, titre, href);
			}
			$(id_popup).dialog('open');
		} else {
			if(!$(id_popup).exists())
			{
				onload();
				$.get(href, function(reponse)
				{
						$('body').append(reponse);
						$.initPopup(id_popup, config, titre, href);
						onloaded();
						$(id_popup).dialog('open');
				}, 'html');
			} else {
				$(id_popup).dialog('open');
			}
		}
	};

	$.initPopup = function(id_popup, config, titre, href)
	{
		var popupId = id_popup;
		var popupConfigSpec = config;
		var popupTitre = titre;
		var ajax = true;
		var href = href;
		var popup = $(popupId);
		
		var infosPopup =
		{
			btnPopupTexte: popupTitre,
			config: objPopups.configDefaut,
			popupId: popupId,
			popup: popup,
			popupConfigSpec: popupConfigSpec,
			popupTitre: popupTitre,
			ajax: ajax,
			href: href
		};
		infosPopup = $.initActionsPopup(infosPopup);
		objPopups.infosPopups.push(infosPopup);
	};
	
	/**
	 * Initialisation des actions d'une popup
	 * $.initActionsPopup(infosPopup);
	 ******************************************/
	$.initActionsPopup = function(infos)
	{
		var btnFermer = infos.popup.find('.btn_fermer');
			
		if(!infos.popupTitre) infos.popupTitre = infos.btnPopupTexte;
		
		// Ajout du titre dynamique
		$.extend(infos.config,
		{
			title: infos.popupTitre
		});
		
		// Fusion avec la configuration spécifique
		if(infos.popupConfigSpec)
		{
			$.extend(infos.config, objPopups[infos.popupConfigSpec]);
		}
		
		// Initialisation
		infos.popup.dialog(infos.config);

		// Création
		infos.config.create(infos.popup);
		
		// Ouverture
		/*infos.btnPopup.click(function()
		{
			infos.popup.dialog('open');
			return false;
		});*/
		
		// Fermeture
		btnFermer.live('click', function()
		{
			infos.popup.dialog('close');
			return false;
		});
			
		return infos;
	};


	/**
	 * Messages d'aide
	 ******************************************/
	$.initPopupsMsgAide = function()
	{
	    var liens = $('a.msg_aide');
	    var popup = $('#popup_msg_aide');
	    var textePopup = popup.find('#texte_popup_msg_aide');
	    var btnPopup = popup.find('#btn_popup_msg_aide');
		var btnTelecharger = $('<a class="btn_telecharger">Télécharger le document</a>');

		popup.dialog(objPopups.configDefaut);

	    liens.live('click', function()
	    {
	    	var lien = $(this);

	        var id_msg_aide = lien.attr('data-msg');
	        var titre_msg_aide = lien.attr('title');
			var url_doc = lien.attr('data-doc');

	        //$(popup).html('<div class="ui-autocomplete-loading popup-loading"></div>');

	        $.getJSON
	        (
	            url_ajax_msg_aide,
	            {
	                id: id_msg_aide,
	                url_doc: url_doc,
	                title: titre_msg_aide
	            },
	            function(json)
	            {
	                var titre = json.titre;
	                var message = json.message;
	                var url = json.url_doc;
	                
	                // Remplace le texte et le bouton de la popup
	                textePopup.html(message);
	               	btnPopup.html('');

	               	if(url_doc)
	               	{
	               		btnTelecharger.attr('href', url_doc);
	                	btnPopup.append(btnTelecharger);
	               	}

	                popup.dialog('option' , 'title' , titre);
	                popup.dialog('open');
	                
	            }
            );

	       // openPopup(popup);

	        return false;
	    });
	};

})(jQuery);