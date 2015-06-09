
/**
 * Initialisation
 ******************************************/
(function($)
{   
    
    $.initUtilisateur = function()
    {
        $.detectTerminal();
    };

    $(document).ready( function()
    {
        $(".select2").select2({
          allowClear: true
        });
            
    });
    
})(jQuery);