var initSignaturePopup = function() {

    $('a.ajout_produit_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
    $('.add_produit_popup_certification_content a#popup_close').click(function() {
        $.fancybox.close();
    });

};

var initProduitChoice = function() {
    $('#application_drm #table_drm_choix_produit input[type="checkbox"]').each(function() {
        if ($(this).attr('class').startsWith('checkbox_all_')) {
            $(this).change(function() {
                var id = $(this).attr('class').replace('checkbox_all_', '');
                var isChecked = $(this).is(':checked');
                $('#application_drm #table_drm_choix_produit .checkbox_' + id).each(function() {
                    if (isChecked)
                        $(this).attr('checked', 'checked');
                    else
                        $(this).removeAttr('checked');
                });
            });
        }
    });
};


var initFilEditionProduit = function() {
    $('.drm_fil_edition_produit').each(function() {
        $(this).click(function() {
            var id = $(this).attr('id');
            $('.col_recolte').each(function() {
                if ($(this).data('hash') == id) {
                    $(this).addClass('col_focus');
                } else {
                    $(this).removeClass('col_focus');
                }
            });
        });
    });
    $('button.btn_colonne_validation').each(function() {

        $(this).click(function() {
            var id = $(this).attr('id').replace('valide_', '');
            $('.drm_fil_edition_produit[id="' + id + '"] > p').addClass('edited');
        });
    });
};



$(document).ready(function()
{
    initFilEditionProduit();
    initProduitChoice();
    initSignaturePopup();
});
