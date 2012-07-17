(function($)
{

	$(document).ready(function()
	{
		$.initAjaxPost();
		$.initAjaxCouchdbForm();
	});

	$.initAjaxPost = function() 
	{

		var notificationError = $('#ajax_form_error_notification');
		var notificationProgress = $('#ajax_form_progress_notification');

		$(document).ajaxError(
			function(event, xhr, settings) {
				if (settings.type === "POST") {
					notificationError.show();
				}
			}
		);

		$(document).ajaxSuccess(
			function(event, xhr, settings) {
				if (settings.type === "POST") {
					notificationError.hide();
				}
			}
		);

		$(document).ajaxSend(
			function(event, xhr, settings) {
				if (settings.type === "POST") {
					notificationError.hide();
					notificationProgress.show();
				}
			}
		);

		$(document).ajaxComplete(
			function(event, xhr, settings) {
				if (settings.type === "POST") {
					notificationProgress.hide();
				}
			}
		);
	};

	$.initAjaxCouchdbForm = function() 
	{
		$(document).ajaxSuccess(
			function(event, xhr, settings) {
				if (settings.type === "POST") {

					var data = null;

					try {
						var data = jQuery.parseJSON(xhr.response);
					} catch (err) {

					}

					if (!(data && data.document && data.document.id && data.document.revision)) {
						
						return ;
					}
					
					$("input[data-id="+ data.document.id + "]").val(data.document.revision);
				}
			}
		);	

	};
})(jQuery);