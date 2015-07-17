var initAjoutProduitPopup = function () {
    $('a.ajout_produit_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        titleShow: false,
    });
    $('a.ajout_produit_popup').click();
    $('.add_produit_popup_certification_content a.popup_close').click(function () {
        $.fancybox.close();

        return false;
    });

};

var initSignatureDrmPopup = function () {

    $('a.signature_drm_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        minWidth: 500
    });
    $('#signature_drm_popup_content a#signature_drm_popup_close').click(function () {
        $.fancybox.close();
    })

    $('#signature_drm_popup_content button#signature_drm_popup_confirm').click(function () {

        $("form#drm_validation").submit();
    });

};

var initCrds = function () {
    $('.drm_crds_list tr.crd_row').each(function () {
        var id = $(this).attr('id');
        var inputs = $('input');

        inputs.saisieNum(false, null, null);

        inputs.click(function()
        {
            $(this).select();
        });

        inputs.change(function () {
            var crds_debut_de_mois = $("#" + id + " td.crds_debut_de_mois input").val();

            var entreesAchats = (!isNaN(parseInt($("#" + id + " td.crds_entreesAchats input").val()))) ? parseInt($("#" + id + " td.crds_entreesAchats input").val()) : 0;
            var entreesRetours = (!isNaN(parseInt($("#" + id + " td.crds_entreesRetours input").val()))) ? parseInt($("#" + id + " td.crds_entreesRetours input").val()) : 0;
            var entreesExcedents = (!isNaN(parseInt($("#" + id + " td.crds_entreesExcedents input").val()))) ? parseInt($("#" + id + " td.crds_entreesExcedents input").val()) : 0;
            var sortiesUtilisations = (!isNaN(parseInt($("#" + id + " td.crds_sortiesUtilisations input").val()))) ? parseInt($("#" + id + " td.crds_sortiesUtilisations input").val()) : 0;
            var sortiesDestructions = (!isNaN(parseInt($("#" + id + " td.crds_sortiesDestructions input").val()))) ? parseInt($("#" + id + " td.crds_sortiesDestructions input").val()) : 0;
            var sortiesManquants = (!isNaN(parseInt($("#" + id + " td.crds_sortiesManquants input").val()))) ? parseInt($("#" + id + " td.crds_sortiesManquants input").val()) : 0;

            var fin_de_mois = parseInt(crds_debut_de_mois) + parseInt(entreesAchats) + parseInt(entreesRetours) + parseInt(entreesExcedents) - parseInt(sortiesUtilisations) - parseInt(sortiesDestructions) - parseInt(sortiesManquants);

            $("#" + id + " td.crds_fin_de_mois").text(fin_de_mois);
        });

    });
}

var initAjoutCrdsPopup = function () {

    $('a.ajout_crds_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        'afterShow': openedPopupAjoutCRD

    });
    $('a.ajout_crds_popup').click();

    $('.add_crds_popup_content a#popup_close').click(function () {
        $.fancybox.close();
    });
};

var openedPopupAjoutCRD = function () {
    console.log('open');
    $('.ui-autocomplete-input').on("focus", function (event, ui) {
        $(this).autocomplete("search");
    });
    $('.ui-autocomplete-input').each(function () {
        var couleur_crd_choice = $(this).parent().children('select').hasClass('couleur_crd_choice');
        console.log(couleur_crd_choice);
        if (couleur_crd_choice) {
            $(this).focus();
        }
    });
};

var initRegimeCrdsPopup = function () {
    $('a.crd_regime_choice_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
	closeClick: false,
	closeBtn: false,
	helpers     : {
            overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
	}
    });
    $('a.crd_regime_choice_popup').click();

};

var initFavoris = function () {
    $('div.groupe span.categorie_libelle').click(function () {
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

var initValidationCoordonneesEtbSociete = function () {
    /*$('a#drm_validation_societe_info_btn').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        titleShow: false
    });

    /*$('#drm_validation_etablissement_info_btn').click(function () {
        $('.drm_validation_etablissement_info').hide();
        $(".drm_validation_etablissement_form").show();
        return false;
    });
    $('#drm_validation_societe_info_btn').click(function () {
        $('.drm_validation_societe_info').hide();
        $(".drm_validation_societe_form").show();
        return false;
    });
    $('#drm_validation_etablissement_annuler_btn').click(function () {
        $('.drm_validation_etablissement_info').show();
        $(".drm_validation_etablissement_form").hide();
        return false;
    });
    $('#drm_validation_societe_annuler_btn').click(function () {
        $('.drm_validation_societe_info').show();
        $(".drm_validation_societe_form").hide();
        return false;
    });*/
}

var initValidationDrmStockMvt = function () {
    $('fieldset#validation_drm_mvts_stocks li.onglet').click(function () {

        var id = $(this).attr('id').replace('_onglet', '');
        if ($(this).children().is('a')) {
            $(this).html('<span>' + $(this).html().replace('<a>', '').replace('</a>', '') + '</span>');
            $(this).addClass('actif');
            $(this).siblings().each(function () {
                $(this).html('<a>' + $(this).html().replace('<span>', '').replace('</span>', '') + '</a>');
                $(this).removeClass('actif');
            });
        }

        $('fieldset#validation_drm_mvts_stocks div.section_label_maj').each(function () {
            $(this).hide();
            if ($(this).attr('id') == id) {
                $(this).show();
            }
        });
    });
}


var callbackAddTemplate = function (bloc)
{

}


var initCollectionNonApurementTemplate = function (element, regexp_replace, callback)
{

    $(element).live('click', function ()
    {
        var bloc_html = $($(this).attr('data-template')).html().replace(regexp_replace, UUID.generate());

        try {
            var params = jQuery.parseJSON($(this).attr('data-template-params'));
        } catch (err) {

        }

        for (key in params) {
            bloc_html = bloc_html.replace(new RegExp(key, "g"), params[key]);
        }

        var bloc = $($(this).attr('data-container')).children('tr').last().after(bloc_html);

        if (callback) {
            callback(bloc);

            $('.champ_datepicker input').initDatepicker();
        }
        return false;
    });
}

var initCollectionDeleteNonApurementTemplate = function ()
{
    $('.drm_non_apurement_delete_row .btn_supprimer_ligne_template').live('click', function ()
    {
        var element = $(this).parent().parent();
        $(element).remove();
        return false;
    });
}

var initNonApurement = function () {
    initCollectionNonApurementTemplate('.ajouter_non_apurement .btn_ajouter_ligne_template', /var---nbItem---/g, callbackAddTemplate);
    initCollectionDeleteNonApurementTemplate();
}

var initTooltips = function () {
    //INIT un tooltip
}

var initBoldSaisie = function () {
    var pattern = '/^[a-z]*(\[[a-z]*\])(\[[a-z]*\])$/i';
    $('input.bold_on_blur').focus(function () {
        var name = $(this).attr('name');
        var matches = name.match(/^[a-z_]*\[([a-z_]+)\]\[([a-z_]+)\]$/);
        var name_header_class = matches[1]+'_'+matches[2];
        $('input.bold_on_blur').each(function () {
            
            if ($(this).attr('name') == name) {
                $(this).attr('style', 'font-weight:bold');

            } else {
                $(this).attr('style', 'font-weight:normal');
            }
        });
        console.log(name_header_class);
        $('span.'+name_header_class).attr('style', 'font-weight:bold')
    });
    $('input.bold_on_blur').blur(function () {
        var name = $(this).attr('name');
        var matches = name.match(/^[a-z_]*\[([a-z_]+)\]\[([a-z_]+)\]$/);
        var name_header_class = matches[1]+'_'+matches[2];
        $('span.'+name_header_class).attr('style', 'font-weight:normal')
    });
}

// init les tooltips dans la colonne intitules
var initMsgAide = function() {

    var msgsAide = $('#colonne_intitules .msg_aide');

    msgsAide.each(function()
    {
        var msgAide = $(this);
        var title = msgAide.attr('title');

        msgAide.removeAttr('title');
        msgsAide.attr('data-title', title);
    });
};

$(document).ready(function ()
{
    initAjoutProduitPopup();
    initAjoutCrdsPopup();
    initRegimeCrdsPopup();
    initCrds();
    initFavoris();
    initValidationDrmStockMvt();
    initValidationCoordonneesEtbSociete();
    initNonApurement();
    initSignatureDrmPopup();
    initTooltips();
    initBoldSaisie();
    initMsgAide();
});
