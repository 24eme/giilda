(function ($)
{
    $(document).ready(function ()
    {
//        $.initFormSection();
//        $.initTags();
//        $.initSearch();
        $.initCoordonneesForms();
    });

    $.initCoordonneesForms = function ()
    {   
        $('#coordonnees_modification .panel-heading span.clickable').on("click", function (e) {
            if ($(this).hasClass('panel-collapsed')) {
                // expand the panel
                $(this).parents('.panel').find('.panel-body').slideDown();
                $(this).removeClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                $(this).find('.label-edit').html('Edition');
            } else {
                // collapse the panel
                $(this).parents('.panel').find('.panel-body').slideUp();
                $(this).addClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                $(this).find('.label-edit').html('Editer');
            }
        });
    };

    // Gère les ouverture / fermeture des blocs de formulaires
//    $.initFormSection = function ()
//    {
//        var formSection = $('#contacts .form_section');
//
//        formSection.children('h3').click(function ()
//        {
//            var formSection = $(this).parent();
//            var formContenu = $(this).siblings('.form_contenu');
//
//            if (formSection.hasClass('ferme'))
//            {
//                formSection.addClass('ouvert');
//                formSection.removeClass('ferme');
//                formContenu.slideDown();
//            } else
//            {
//                formSection.addClass('ferme');
//                formSection.removeClass('ouvert');
//                formContenu.slideUp();
//            }
//        });
//    };

//    $.initTags = function ()
//    {
//        $('#contacts .tags').tagit();
//    };

//    $.initSearch = function ()
//    {
//        $('a.tags_more').click(function () {
//            $(this).parent().parent().find("li.tag_overflow").toggle();
//
//            var libelle = $(this).html();
//            $(this).html($(this).attr('data-toggle-text'));
//            $(this).attr('data-toggle-text', libelle);
//
//            $(this).blur();
//
//            return false;
//        });
//    }

})(jQuery);


