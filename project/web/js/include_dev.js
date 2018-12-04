/**
 * Fichier : includes.js
 * Description : Inclusion de fichiers JS
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

(function($)
{
	/**
	 * Gère l'inclusion de fichier JS
	 * $.fn.include(chemin, fichier, {condition: false, operateur: '', version: ''});
	 ******************************************/
	$.fn.includeJS = function(chemin, fichier, opt)
	{
		var options =
		{
			condition: false,
			operateur: '',
			version: ''
		};

		if(opt) options = $.extend(options, opt);

		if(options.condition)
		{
			document.write('\n\t<!--[if '+ options.operateur +' '+ options.version +']><script type="text/javascript" src="' + chemin + fichier + '"></scr' + 'ipt><![endif]-->');
		}
		else
		{
			document.write('\n\t<script type="text/javascript" src="' + chemin + fichier + '"></scr' + 'ipt>');
		}
	};

	/**
	 * Inclusions
	 ******************************************/
	// Librairies
	$.fn.includeJS(jsPath, 'lib/jquery-ui-1.8.21.min.js');

	// Plugins
	$.fn.includeJS(jsPath, 'plugins/selectivizr-min.js', {condition: true, operateur: 'lte', version: 'IE 8'});
	$.fn.includeJS(jsPath, 'plugins/jquery.plugins.min.js');

	// Fonctions personnalisées)
	$.fn.includeJS(jsPath, 'global.js');
	$.fn.includeJS(jsPath, 'popups.js');
	$.fn.includeJS(jsPath, 'autocomplete.js');
    $.fn.includeJS(jsPath, 'ajaxHelper.js');
	$.fn.includeJS(jsPath, 'vrac.js?20170922');
	$.fn.includeJS(jsPath, 'form.js');
	$.fn.includeJS(jsPath, 'drm.js');
	$.fn.includeJS(jsPath, 'declaration.js');
	$.fn.includeJS(jsPath, 'sv12.js');
	$.fn.includeJS(jsPath, 'ds.js');
    $.fn.includeJS(jsPath, 'colonnes.js');
	$.fn.includeJS(jsPath, 'contacts.js');
	$.fn.includeJS(jsPath, 'hamza_style.js');
        $.fn.includeJS(jsPath, 'societe.js');
        $.fn.includeJS(jsPath, 'etablissement.js');
        $.fn.includeJS(jsPath, 'teledeclaration_vrac.js?20170922');
        $.fn.includeJS(jsPath, 'teledeclaration_drm.js?201812041044');
        $.fn.includeJS(jsPath, 'produits.js');

})(jQuery);
