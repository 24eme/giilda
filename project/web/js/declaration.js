/**
 * Fichier : déclaration.js
 * Description : fonctions JS spécifiques à la déclaration
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/

(function($)
{
	var anchorIds = {"entrees" : 2, "sorties" : 3};
	// Variables globales

	var selectProduit = $('#produit_declaration_hashref');

	var colonnesDR = $('#colonnes_dr');
	var btnAjouter = colonnesDR.find('.btn_ajouter');
	
	var btnEtapesDR = $('#btn_etape_dr');
	var btnPrecSuivProd = $('#btn_suiv_prec');

    var colonnes;

//
//	var actifPopupLien;

	$(document).ready( function()
	{

        //$('.autocomplete').combobox();

		if(colonnesDR.length)
		{
            colonnes = new $.Colonnes();
            colonnes.init();

            $.initProduitForm();
            //$.initDetailsPopups();

            if (colonnes.colonnes.length > 1) {
                var colonne = colonnes.colonnes[1];
                colonne.focus();
                colonne.focusChampDefault();
            }
		}
	});


	/**
	 * Initialise l'ajax pour le formulaire d'ajout d'un produit
	 * $.initProduitForm();
	 ******************************************/
	$.initProduitForm = function() {
		var formProduit = $('#form_produit_declaration');

		selectProduit.find('optgroup[label=existant]').addClass('existant');
		//selectProduit.combobox();

		selectProduit.change(function() {
			formProduit.submit();
		});

		formProduit.submit(function() {
			var selected = selectProduit.find('option:selected');
            if(!selected.parent('optgroup').hasClass('existant')) {

                return true;
            }
			colonne = colonnes.findByHash(selected.val());
			colonne.focus();
			colonne.focusChampDefault();
			selected.removeAttr('selected');
			selectProduit.parent().find('.ui-autocomplete-input').val('');

			return false;
		});

        $('#list-produits a').click(function() {
            colonne = colonnes.findByHash($(this).attr('data-hash'));
            colonne.focus();
            colonne.focusChampDefault();

            return false;
        });
	}

        /**
     * Contrôle la bonne saisie de nombres dans
     * un champ
     * $(s).saisieNum(float, callbackKeypress);
     ******************************************/
    $.fn.saisieNum = function (float, callbackKeypress, callbackBlur)
    {
        var champ = $(this);

        // A chaque touche pressée
        champ.keypress(function (e)
        {
            var val = $(this).val();
            var touche = e.which;
            var ponctuationPresente = (val.indexOf('.') != -1 || val.indexOf(',') != -1);
            var chiffre = (touche >= 48 && touche <= 57); // Si chiffre

            // touche "entrer"
            if (touche == 13)
                return e;

            // touche "entrer"
            if (touche == 0)
                return e;

            // Champ nombre décimal
            if (float)
            {
                // !backspace && !null && !point && !virgule && !chiffre
                if (touche != 8 && touche != 0 && touche != 46 && touche != 44 && !chiffre)
                    return false;
                // point déjà présent
                if (touche == 46 && ponctuationPresente)
                    e.preventDefault();
                // virgule déjà présente
                if (touche == 44 && ponctuationPresente)
                    e.preventDefault();
                // 2 décimales
                if (val.match(/[\.\,][0-9][0-9]/) && chiffre && e.currentTarget && e.currentTarget.selectionStart > val.length - 3)
                    e.preventDefault();
            }
            // Champ nombre entier
            else
            {
                if (touche != 8 && touche != 0 && !chiffre)
                    e.preventDefault();
            }

            if (callbackKeypress)
                callbackKeypress();
            return e;
        });

        // A chaque touche pressée
        champ.keyup(function (e)
        {
            var touche = e.which;

            // touche "retour"
            if (touche == 8)
            {
                if (callbackKeypress)
                    callbackKeypress();
                return e;
            }
        });


        // A chaque fois que l'on quitte le champ
        champ.blur(function ()
        {
            $(this).nettoyageChamps();
            if (callbackBlur)
                callbackBlur();
        });
    };


    /**
     * Nettoie les champs après la saisie
     * $(champ).nettoyageChamps();
     ******************************************/
    $.fn.nettoyageChamps = function ()
    {
        var champ = $(this);
        var val = champ.attr('value');
        var float = champ.hasClass('num_float');
        var champ_int = champ.hasClass('num_int');

        // Si quelque chose a été saisi
        if (val)
        {
            // Remplacement de toutes les virgules par des points
            if (val.indexOf(',') != -1)
                val = val.replace(',', '.');

            // Si un point a été saisi sans chiffre
            if (val.indexOf('.') != -1 && val.length == 1)
                val = ''; //val = '0';

            // Un nombre commençant par 0 peut être interprété comme étant en octal
            if (val.indexOf('0') == 0 && val.length > 1)
                val = val.substring(1);

            // Comparaison nombre entier / flottant
            if (float || parseInt(val) != parseFloat(val) && !champ_int)
                val = parseFloat(val).toFixed(2);
            else
                val = parseInt(val);
        }
        // Si rien n'a été saisi
        //else val = 0;
        else
            val = '';

        // Si ce n'est pas un nombre (ex : copier/coller d'un texte)
        if (isNaN(val))
            val = ''; //val = 0;

        /*if (val == 0) {
         champ.addClass('num_light');
         } else {
         champ.removeClass('num_light');
         }*/
        champ.attr('value', val);
    };

    $.fn.nettoyageChampsWithFourPrecision = function ()
    {
        var champ = $(this);
        var val = champ.attr('value');
        var float = champ.hasClass('num_float');

        // Si quelque chose a été saisi
        if (val)
        {
            // Remplacement de toutes les virgules par des points
            if (val.indexOf(',') != -1)
                val = val.replace(',', '.');

            // Si un point a été saisi sans chiffre
            if (val.indexOf('.') != -1 && val.length == 1)
                val = ''; //val = '0';

            // Un nombre commençant par 0 peut être interprété comme étant en octal
            if (val.indexOf('0') == 0 && val.length > 1)
                val = val.substring(1);

            // Comparaison nombre entier / flottant
            if (float || parseInt(val) != parseFloat(val))
                val = parseFloat(val).toFixed(4);
            else
                val = parseInt(val);
        }
        // Si rien n'a été saisi
        //else val = 0;
        else
            val = '';

        // Si ce n'est pas un nombre (ex : copier/coller d'un texte)
        if (isNaN(val))
            val = ''; //val = 0;

        /*if (val == 0) {
         champ.addClass('num_light');
         } else {
         champ.removeClass('num_light');
         }*/
        champ.attr('value', val);
    };


	/**
	 * Initialise l'activation d'un colonne
	 ******************************************/
	$.initColActive = function()
	{
		if(colActiveDefaut.exists()) {
			colActiveDefaut.majColActive();
		}
	};
	
        
    $.fn.initDetailsPopup = function(colonne){
                
            var input = $(this); 
            
            //$('.autocomplete').combobox();
            $('.champ_datepicker input').initDatepicker();
            $.majSommeLabel();
            $.bindAddTemplateLien();

            $('.drm_details_form').bind('submit', function()
            {
                $.post($(this).attr('action'),
                        $(this).serialize(),
                        function(data)
                        {
                            if(!data.success)
                            {
                            $('.drm_details_form_content').html(data.content);
                            $('.autocomplete').combobox();
                            $('.champ_datepicker input').initDatepicker();
                            $.majSommeLabel();
                            $.bindAddTemplateLien();
                            $.fancybox.update();
                            }
                            else
                            {
                            input.val(data.volume);
                            input.nettoyageChamps();
                            input.attr('data-val-defaut',input.val());
                            colonne.active();
                            colonne.calculer();
                            $.fancybox.close();
			    $.fn.RevisionajaxSuccessCallBackData = colonne;
			    $.fn.RevisionajaxSuccessCallBack = function () {
				$.fn.RevisionajaxSuccessCallBackData.valider();
			    }
                            }
                        }, "json");

                return false;
            });
         };
        
        $.majSommeLabel = function()
        {
	    $.majSommeLabelBind = function()
            {
                var vol = 0;
                $('.drm_details_tableBody td.volume').each(function()
                {
                    var vol_val = $(this).children('input').val();
                    if(vol_val=='') vol_val = 0;
                    var vol_val_float = parseFloat(vol_val);
                    if(isNaN(vol_val_float)) return true;
                    vol+=vol_val_float;
                }); 
                $('.drm_details_volume_total').text(vol.toFixed(2));
            }
            $('.drm_details_tableBody td.volume').unbind();
            $('.drm_details_tableBody td.volume').bind('keyup', $.majSommeLabelBind);
        }
         	
})(jQuery);