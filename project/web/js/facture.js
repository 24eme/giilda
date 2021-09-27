var initCollectionDeleteMouvementsFactureTemplate = function ()
{
    $('.mouvements_facture_delete_row .btn_supprimer_ligne_template').click(function ()
    {
        var element = $(this).parent().parent().parent().parent();
        if (element.parent().children('.mvt_ligne').size() > 1) {
            $(element).remove();

        }
        return false;
    });
}

$(document).ready(function ()
{
    initCollectionDeleteMouvementsFactureTemplate();
    $('.btn-suppr-ligne').click(function () {
        $(this).parents('.row').remove();
    });
});
