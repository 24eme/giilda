(function($)
{
	$(document).ready(function()
	{
		$.initFormSection();
		$.initTags();
		$.initSearch();
	});
	
	// Gère les ouverture / fermeture des blocs de formulaires
	$.initFormSection = function()
	{
		var formSection = $('#contacts .form_section');
		
		formSection.children('h3').click(function()
		{
			var formSection = $(this).parent();
			var formContenu = $(this).siblings('.form_contenu');
			
			if(formSection.hasClass('ferme'))
			{
				formSection.addClass('ouvert');
				formSection.removeClass('ferme');
				formContenu.slideDown();
			}else
			{
				formSection.addClass('ferme');
				formSection.removeClass('ouvert');
				formContenu.slideUp();
			}
		});
	};
	
	$.initTags = function()
	{
		$('#contacts .tags').tagit();
	};

	$.initSearch = function()
	{
		$('a.tags_more').click(function() {
			$(this).parent().parent().find("li.tag_overflow").toggle();

			var libelle = $(this).html();
			$(this).html($(this).attr('data-toggle-text'));
			$(this).attr('data-toggle-text', libelle);

			$(this).blur();

			return false;
		});
	}
	
})(jQuery);


