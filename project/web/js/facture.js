

var initCollectionMouvementsFactureTemplate = function (element, regexp_replace, callback)
{

    $(element).on('click', function ()
    {
        var uuid = UUID.generate();
        var bloc_html = $($(this).attr('data-template')).html().replace(regexp_replace, uuid);


        var inputsToGetValues = $($(this).attr('data-container')).children('div').last().find('input');

        var bloc = $($(this).attr('data-container')).children('div').last().after(bloc_html);


        $($(this).attr('data-container')).children('div').last().find('input').each(function () {
            var name = $(this).attr('name');
            var value = "";
            if ((name != undefined) && (name.contains(uuid))) {
                var nameReduct = name.substring(name.lastIndexOf("["));
                inputsToGetValues.each(function () {
                    var inputName = $(this).attr('name');
                    if ((inputName != undefined) && (inputName.contains(nameReduct))) {

                        if (nameReduct != "[identifiant]") {
                        
                            value = $(this).val();
                        }
                    }
                });
            }
            $(this).val(value);
        });

        $($(this).attr('data-container')).children('div').find('input').each(function () {
            var name = $(this).attr('name');
            if (name != undefined) {
                if ($(this).val() && name.substring(name.lastIndexOf("[")) == "[identifiant]") {
                    var new_value = $(this).val();
                    $(this).val(new_value + "," + $(this).parent().find('.select2-container').find('.select2-chosen').text());
                }
            }
        });



        if (callback) {
            callback(bloc);
            initCollectionDeleteMouvementsFactureTemplate();
               $(document).initAdvancedElements();
        }
        return false;
    });
}

var initCollectionDeleteMouvementsFactureTemplate = function ()
{
    $('.mouvements_facture_delete_row .btn_supprimer_ligne_template').on('click', function ()
    {
        var element = $(this).parent().parent().parent().parent();
        if (element.parent().children('.mvt_ligne').size() > 1) {
            $(element).remove();

        }
        return false;
    });
}

var initMouvementsFacture = function () {
    initCollectionMouvementsFactureTemplate('.ajouter_mouvement_facture .btn_ajouter_ligne_template', /var---nbItem---/g, function (bloc) {
    });
    initCollectionDeleteMouvementsFactureTemplate();
}



$(document).ready(function ()
{
    initMouvementsFacture();
});