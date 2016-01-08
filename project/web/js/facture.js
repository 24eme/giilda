
var generateMouvementsFacture = function (element, regexp_replace, callback)
{
    if ($(element).length) {

        var uuid = UUID.generate();
        var bloc_html = $($(element).attr('data-template')).html().replace(regexp_replace, "nouveau_"+uuid);

        var inputsToGetValues = $(element).children('div').last().find('input');
        var selectsToGetValues = $(element).children('div').last().find('select');

        var bloc = $(element).children('div').last().after(bloc_html);


        $(element).children('div').last().find('input').each(function () {
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

        $(element).children('div').last().find('select').each(function () {
            var valueSelected = $(selectsToGetValues).find('option[selected="selected"]').val();
            $(this).find('option[value="' + valueSelected + '"]').attr('selected', 'selected');
        });

        $(element).children('div').find('input').each(function () {
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
            initCollectionMouvementsFactureTemplate(element, regexp_replace, callback);
        }
        return false;
    }
}

var isConformForNewLine = function (element) {
    var result = true;
    $(element).children('div').last().find('input').each(function () {
        if ($(this).attr('name') != undefined) {
            if (($(this).val() == null) || ($(this).val() == "")) {
                result = false;
            }
        }
    });
    $(element).children('div').last().find('select').each(function () {
        if (($(this).attr('name') != undefined) && (($(this).val() == null) || ($(this).val() == ""))) {
            result = false;
        }
    });
    return result;
}

var initCollectionMouvementsFactureTemplate = function (element, regexp_replace, callback)
{
    var lastRowInputs = $(element).children('div').last().find('input');
    var lastRowSelects = $(element).children('div').last().find('input');

    $(lastRowInputs).change(function () {
        var addNewLine = isConformForNewLine(element);

        if (addNewLine) {
            generateMouvementsFacture(element, regexp_replace, callback);
        }

    });

    $(lastRowSelects).change(function () {
        var addNewLine = isConformForNewLine(element);

        if (addNewLine) {
            generateMouvementsFacture(element, regexp_replace, callback);
        }

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
    generateMouvementsFacture('#mouvementsfacture_list', /var---nbItem---/g, function (bloc) {
    });
    initCollectionMouvementsFactureTemplate('#mouvementsfacture_list', /var---nbItem---/g, function (bloc) {
    });
    initCollectionDeleteMouvementsFactureTemplate();
}



$(document).ready(function ()
{
    initMouvementsFacture();
});