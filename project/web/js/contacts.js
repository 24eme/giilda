(function($)
{
	$(document).ready(function()
	{
		$.initFormSection();
	});
	
	// Gère les ouverture / fermeture des blocs de formulaires
	$.initFormSection = function()
	{
		var formSection = $('.form_section');
		
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
	
})(jQuery);


