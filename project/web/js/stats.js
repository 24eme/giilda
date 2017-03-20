var initCollectionDeleteTemplate = function()
{
	$('#advancedFilters').on('click', 'a.btn_rm_ligne_template', function()
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
var initStatistiques = function()
{
	var form = $("#statistiques-form");
	var formAction = form.attr('action')
	var csvLink = $("#statistiques-csv");

	csvLink.on('click', function() {
		var link = csvLink.attr('href');
		form.attr('action', link);
		form.submit();
		form.attr('action', formAction);
        return false;
    });
}
$(document).ready(function ()
{
    if ($('#statistiques').length > 0) {
    	initCollectionAddTemplate('.btn_ajouter_ligne_template', /var---nbItem---/g, null);
    	initCollectionDeleteTemplate();
        initStatistiques();
    }
    if ($("#resetBtn").length > 0) {
    	$("#resetBtn").css('display', 'none');
    }
    $("#stat_choices input[type='radio']").on('click', function () {
    	$("#stat_choices input[type='radio']").each(function() {
    		$(this).removeAttr('checked');
    	});
    	$("#resetBtn").trigger("click");
    	$(".select2-search-choice").remove();
    	$(this).attr("checked", "checked");
    });
});