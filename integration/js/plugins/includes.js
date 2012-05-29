/**
 * Fichier : includes.js
 * Description : Inclusion de fichiers JS
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

(function($)
{
	/**
	 * G�re l'inclusion de fichier JS
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
	$.fn.includeJS(jsPath, 'lib/jquery-ui-1.8.1.min.js');
	
	// Plugins
	$.fn.includeJS(jsPath, 'plugins/selectivizr-min.js', {condition: true, operateur: 'lte', version: 'IE 8'});
	$.fn.includeJS(jsPath, 'plugins/jquery.plugins.min.js');
	$.fn.includeJS(jsPath, 'plugins/ui.dropdownchecklist-1.3-min.js');
		
	// Fonctions personnalisées)
	$.fn.includeJS(jsPath, 'global.js');
	$.fn.includeJS(jsPath, 'popups.js');
	$.fn.includeJS(jsPath, 'autocomplete.js');
	$.fn.includeJS(jsPath, 'contrat.js');
	$.fn.includeJS(jsPath, 'drm.js');
	$.fn.includeJS(jsPath, 'declaration.js');

})(jQuery);