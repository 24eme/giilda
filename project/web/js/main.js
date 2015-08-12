
/**
 * Initialisation
 ******************************************/
(function($)
{   
    $(document).ready( function()
    {
        $(".select2").select2({
          allowClear: true
        });
        
        $('.input-group.date').datetimepicker({
            locale: 'fr_FR',
            format: 'L',
            allowInputToggle: true,
            focusOnShow: true,
            useCurrent: false
        });
    });
    
})(jQuery);