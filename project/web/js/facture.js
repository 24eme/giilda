

var initCollectionMouvementsFactureTemplate = function (element, regexp_replace, callback)
{

    $(element).on('click', function ()
    {
        var bloc_html = $($(this).attr('data-template')).html().replace(regexp_replace, UUID.generate());

        try {
            var params = jQuery.parseJSON($(this).attr('data-template-params'));
        } catch (err) {

        }

        for (key in params) {
            bloc_html = bloc_html.replace(new RegExp(key, "g"), params[key]);
        }

        var bloc = $($(this).attr('data-container')).children('div').last().after(bloc_html);

        if (callback) {
            callback(bloc);
            initCollectionDeleteMouvementsFactureTemplate();
        }
        return false;
    });
}

var initCollectionDeleteMouvementsFactureTemplate = function ()
{
    $('.mouvements_facture_delete_row .btn_supprimer_ligne_template').on('click', function ()
    {
        var element = $(this).parent().parent().parent().parent();
        if(element.parent().children('.mvt_ligne').size() > 1){
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