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

var initCrds = function() {
    $('.drm_crds_list tr.crd_row').each(function() {
        var id = $(this).attr('id');
        $('input').change(function() {
            var crds_debut_de_mois = $("#" + id + " td.crds_debut_de_mois input").val();
            var entrees = $("#" + id + " td.crds_entrees input").val();
            var sorties = $("#" + id + " td.crds_sorties input").val();
            var pertes = $("#" + id + " td.crds_pertes input").val();
            var fin_de_mois = parseInt(crds_debut_de_mois) + parseInt(entrees) - parseInt(sorties) - parseInt(pertes);
            $("#" + id + " td.crds_fin_de_mois").text(fin_de_mois);
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

var initFavoris = function() {
    $('div.groupe span.categorie_libelle').click(function() {
        var id_fav_input = $(this).attr('id').replace('star_', 'drmFavoris_');
        var value = $('#colonne_intitules form #' + id_fav_input).val();
        if (value === "1") {
            $('#colonne_intitules form #' + id_fav_input).val("");
        }
        else {
            $('#colonne_intitules form #' + id_fav_input).val("1");
        }
        $("#colonne_intitules form").submit();
    });
}

var initValidationCoordonneesEtbSociete = function() {
    $('#drm_validation_etablissement_info_btn').click(function() {
        $('.drm_validation_etablissement_info').hide();
        $(".drm_validation_etablissement_form").show();
        return false;
    });
    $('#drm_validation_societe_info_btn').click(function() {
        $('.drm_validation_societe_info').hide();
        $(".drm_validation_societe_form").show();
        return false;
    });
    $('#drm_validation_etablissement_annuler_btn').click(function() {
        $('.drm_validation_etablissement_info').show();
        $(".drm_validation_etablissement_form").hide();
        return false;
    });
    $('#drm_validation_societe_annuler_btn').click(function() {
        $('.drm_validation_societe_info').show();
        $(".drm_validation_societe_form").hide();
        return false;
    });
}

var initValidationDrmStockMvt = function() {
    $('fieldset#validation_drm_mvts_stocks li.onglet').click(function() {

        var id = $(this).attr('id').replace('_onglet', '');
        if ($(this).children().is('a')) {
            $(this).html('<span>' + $(this).html().replace('<a>', '').replace('</a>', '') + '</span>');
            $(this).addClass('actif');
            $(this).siblings().each(function() {
                $(this).html('<a>' + $(this).html().replace('<span>', '').replace('</span>', '') + '</a>');
                $(this).removeClass('actif');
            });
        }

        $('fieldset#validation_drm_mvts_stocks div.section_label_maj').each(function() {
            $(this).hide();
            if ($(this).attr('id') == id) {
                $(this).show();
            }
        });
    });
}

$(document).ready(function()
{
    initFilEditionProduit();
    initAjoutProduitPopup();
    initAjoutCrdsPopup();
    initCrds();
    initFavoris();
    initValidationDrmStockMvt();
    initValidationCoordonneesEtbSociete();

});
