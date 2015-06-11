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

            var entreesAchats = $("#" + id + " td.crds_entreesAchats input").val();
            var entreesRetours = $("#" + id + " td.crds_entreesRetours input").val();
            var entreesExcedents = $("#" + id + " td.crds_entreesExcedents input").val();

            var sortiesUtilisations = $("#" + id + " td.crds_sortiesUtilisations input").val();
            var sortiesDestructions = $("#" + id + " td.crds_sortiesDestructions input").val();
            var sortiesManquants = $("#" + id + " td.crds_sortiesManquants input").val();

            var fin_de_mois = parseInt(crds_debut_de_mois) + parseInt(entreesAchats)+ parseInt(entreesRetours)+ parseInt(entreesExcedents) - parseInt(sortiesUtilisations) - parseInt(sortiesDestructions) - parseInt(sortiesManquants);
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

var initRegimeCrdsPopup = function() {

    $('a.crd_regime_choice_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto'
    });
    $('a.crd_regime_choice_popup').click();

};


var initFilEditionProduit = function() {

    $('.drm_fil_edition_produit').on('click', 'a', function(e)
    {
        var parent = $(this).parent();
        var id = parent.attr('id');

        e.preventDefault();

        parent
        .addClass('current')
        .siblings('li')
        .removeClass('current');

        $('.col_recolte').each(function() {
            if ($(this).data('hash') == id) {
                $(this).addClass('col_focus');
            } else {
                $(this).removeClass('col_focus');
            }
        });
    });

    $('button.btn_colonne_validation').each(function() {

        $(this).click(function() {
            var id = $(this).attr('id').replace('valide_', '');
            $('.drm_fil_edition_produit[id="' + id + '"]').addClass('edited');
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
    initRegimeCrdsPopup();
    initCrds();
    initFavoris();
    initValidationDrmStockMvt();
    initValidationCoordonneesEtbSociete();

});
