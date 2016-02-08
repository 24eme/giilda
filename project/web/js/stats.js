var initCollectionDeleteTemplate = function()
{
	$('.btn_rm_ligne_template').on('click',function()
    {
    	var element = $(this).attr('data-container');
        $(this).parents(element).remove();
        return false;
    });
}

var initCollectionAddTemplate = function(element, regexp_replace, callback)
{
    $(element).on('click', function()
    {
        var bloc_html = $($(this).attr('data-template')).html().replace(regexp_replace, UUID.generate());
        try {
			var params = jQuery.parseJSON($(this).attr('data-template-params'));
		} catch (err) {

        }
		for(key in params) {
			bloc_html = bloc_html.replace(new RegExp(key, "g"), params[key]);
		}
        var bloc = $($(this).attr('data-container')).append(bloc_html);
        if(callback) {
        	callback(bloc);
        }
        return false;
    });
}
$(document).ready(function ()
{
	initCollectionAddTemplate('.btn_ajouter_ligne_template', /var---nbItem---/g, null);
	initCollectionDeleteTemplate();
});