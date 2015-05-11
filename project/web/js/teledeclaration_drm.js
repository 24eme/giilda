var initAjoutProduitPopup = function() {

    $('a.ajout_produit_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
    $('.add_crds_popup_content a#popup_close').click(function() {
        $.fancybox.close();
    });

};

var initCrds = function(){
    $('.drm_crds_list tr.crd_row').each(function(){
        var id = $(this).attr('id');
        $('input').change(function(){            
            var crds_debut_de_mois = $("#"+id+" td.crds_debut_de_mois input").val();
            var entrees = $("#"+id+" td.crds_entrees input").val();
            var sorties = $("#"+id+" td.crds_sorties input").val();
            var pertes = $("#"+id+" td.crds_pertes input").val();
            var fin_de_mois = parseInt(crds_debut_de_mois) + parseInt(entrees) - parseInt(sorties) - parseInt(pertes);
            $("#"+id+" td.crds_fin_de_mois").text(fin_de_mois);
        });
        
    });
}

var initAjoutCrdsPopup = function() {

    $('a.ajout_crds_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
    });
    $('.add_crds_popup_content a#popup_close').click(function() {
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
    initAjoutProduitPopup();
    initAjoutCrdsPopup();
    initCrds();
});
