/**
 * Fichier : includes.js
 * Description : Inclusion de fichiers JS
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/
var date = new Date();

yepnope
(
	// Chargement jQuery 1.7.2
	{
		load: 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js',
		complete: function()
		{
			// Fallback
			if(!window.jQuery)
			{
				yepnope(jsPath+'lib/jquery-1.7.2.min.js');
			}
			
			// Selectivzr IE < 9
			yepnope ('ielt9!'+jsPath+'plugins/selectivizr-min.js');
			
			
			
			// Plugins jQuery
			yepnope
			({
				load:
				[
					jsPath+'lib/jquery-ui-1.8.1.min'
				],
				complete: function()
				{
					yepnope
					({					
						load:
						[
							jsPath+'autocomplete.js?v='+date,
							jsPath+'ajaxHelper.js?v='+date,
							jsPath+'vrac.js?v='+date,
							jsPath+'sv12.js?v='+date,
                                                        jsPath+'etablissement.js?v='+date,
							jsPath+'plugins/jquery.plugins.min.js?v='+date,
							jsPath+'global.js?v='+date,
                                                        jsPath+'produits.js?v='+date
						],
						
						complete: function()
						{
							/*if($('#rub_contrats').exists())
							{
								yepnope(jsPath+'contrats.js?v='+date);
							}*/
						}
					});
				}	
			});
		}
	}
);