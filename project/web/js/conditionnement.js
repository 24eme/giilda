/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($)
{

	$.fn.initBlocCondition = function()
	{
		$(this).find('.bloc_condition').each(function() {
			checkUncheckCondition($(this));
		});
	}

	var checkUncheckCondition = function(blocCondition)
    {
    	var input = blocCondition.find('input');
    	if(input.length == 0) {
    		input = blocCondition.find('select');
    	}
    	var blocs = blocCondition.attr('data-condition-cible').split('|');
    	var traitement = function(input, blocs) {
        	   for (bloc in blocs) {
        		   if ($(blocs[bloc]).size() > 0) {
            		   var values = $(blocs[bloc]).attr('data-condition-value').split('|');
            		   for(key in values) {
            			   if (input.attr('type') == 'checkbox') {
            				   if (values[key] == 1 && input.is(':checked')) {
            					   $(blocs[bloc]).show();
            				   }
            				   if (values[key] != 1 && !input.is(':checked')) {
            					   $(blocs[bloc]).show();
            				   }
            				   
            			   }
            			   if (values[key] == input.val() && (input.is(':checked')) || input.is(':selected')) {
            				   $(blocs[bloc]).show();
            			   }
            		   }
        		   }
        	   }
    	}
    	if(input.length == 0) {
     	   for (bloc in blocs) {
  				$(blocs[bloc]).show();
     	   }
    	} else {
     	   for (bloc in blocs) {
  				$(blocs[bloc]).hide();
     	   }
    	}
    	input.each(function() {
    		traitement($(this), blocs);
    	});

        input.click(function()
        {
      	   for (bloc in blocs) {
 				$(blocs[bloc]).hide();
    	   }
      	   if($(this).is(':checkbox')) {
          	   $(this).parent().find('input').each(function() {
	        	   traitement($(this), blocs);
          	   });
      	   } else {
      		   traitement($(this), blocs);
      	   }
        });
	}
	
	$(document).ready(function()
	{
		 $(this).initBlocCondition();
	});
})(jQuery);