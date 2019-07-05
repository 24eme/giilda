
var initAjoutProduitPopup = function () {
    $('.choix_produit_add_produit .submit_button').click(function () {
        $('.drm #form_choix_produits').attr('action', $(this).attr('href'));
        $('.drm #form_choix_produits').submit();
        return false;
    });

    $('a.ajout_produit_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        titleShow: false,
        'afterShow': $.initProtectForms
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

    $('#signature_drm_popup_content a#signature_drm_popup_confirm').click(function () {
        $("form#drm_validation input#drm_email_transmission").val($('#drm_email_transmission_visible').val());
        $("form#drm_validation input#drm_transmission_ciel").val($('#drm_transmission_ciel_visible').is(':checked'));
        $("form#drm_validation").submit();
    });

};

var initCrds = function () {
    $('.drm_crds_list tr.crd_row').each(function () {
        var id = $(this).attr('id');

        var inputs = $(this).children('td').children('input');

        updateCrdsTotaux(id);

        inputs.saisieNum(false, null, null);

        inputs.click(function ()
        {
            $(this).select();
        });

        inputs.blur(function () {
            updateCrdsTotaux(id);
        });

    });
}

var updateCrdsTotaux = function (id) {

    var crds_debut_de_mois = $("#" + id + " td.crds_debut_de_mois input").val();

    var entreesAchats = (!isNaN(parseInt($("#" + id + " td.crds_entreesAchats input").val()))) ? parseInt($("#" + id + " td.crds_entreesAchats input").val()) : 0;
    var entreesRetours = (!isNaN(parseInt($("#" + id + " td.crds_entreesRetours input").val()))) ? parseInt($("#" + id + " td.crds_entreesRetours input").val()) : 0;
    var entreesExcedents = (!isNaN(parseInt($("#" + id + " td.crds_entreesExcedents input").val()))) ? parseInt($("#" + id + " td.crds_entreesExcedents input").val()) : 0;
    var sortiesUtilisations = (!isNaN(parseInt($("#" + id + " td.crds_sortiesUtilisations input").val()))) ? parseInt($("#" + id + " td.crds_sortiesUtilisations input").val()) : 0;
    var sortiesDestructions = (!isNaN(parseInt($("#" + id + " td.crds_sortiesDestructions input").val()))) ? parseInt($("#" + id + " td.crds_sortiesDestructions input").val()) : 0;
    var sortiesManquants = (!isNaN(parseInt($("#" + id + " td.crds_sortiesManquants input").val()))) ? parseInt($("#" + id + " td.crds_sortiesManquants input").val()) : 0;

    var fin_de_mois = parseInt(crds_debut_de_mois) + parseInt(entreesAchats) + parseInt(entreesRetours) + parseInt(entreesExcedents) - parseInt(sortiesUtilisations) - parseInt(sortiesDestructions) - parseInt(sortiesManquants);

    $("#" + id + " td.crds_fin_de_mois").text(fin_de_mois);
}

var initAjoutCrdsPopup = function () {
    $('.drm_add_crd_categorie .submit_button').click(function () {
        $('.drm #form_crds').attr('action', $(this).attr('href'));
        $('.drm #form_crds').submit();
        return false;
    });

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
    $('.ui-autocomplete-input').on("focus", function (event, ui) {
        $(this).autocomplete("search");
    });
    $('.ui-autocomplete-input').each(function () {
        var couleur_crd_choice = $(this).parent().children('select').hasClass('couleur_crd_choice');
        if (couleur_crd_choice) {
            $(this).focus();
        }
    });
};

var initLegalSignaturePopup = function () {
    $('a.legal_signature_popup').fancybox({
//        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: '50%',
        closeClick: false,
        closeBtn: false,
        helpers: {
            overlay: {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
        }
    });
    $('a.legal_signature_popup').click();

};


var initRegimeCrdsPopup = function () {
    $('a.crd_regime_choice_popup').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        closeClick: false,
        closeBtn: false,
        helpers: {
            overlay: {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
        }
    });
    $('a.crd_regime_choice_popup').click();

};

var initCreationDrmPopup = function () {

    $('a.drm_nouvelle_teledeclaration').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        minWidth: 500,
        'afterShow': $.initProtectForms

    });
    $('.popup_contenu a#drm_nouvelle_popup_close').click(function () {
        $.fancybox.close();
    });

    $('.popup_creation_drm div.type_creation input').change(function () {
        var value = $(this).attr('value');
        var id_drm = $(this).parents('div').attr('id').replace('type_creation_div_', '');

        console.log($('#file_edi_div_' + id_drm));
        if (value == 'CREATION_EDI') {
            $('#file_edi_div_' + id_drm).show();
        } else {
            $('#file_edi_div_' + id_drm).hide();
        }

    });
    $('.popup_creation_drm div.type_creation label').click(function () {
        $(this).siblings('input').click();
    });

};

var initDeleteDrmPopup = function () {

    $('a.drm_delete_lien_colonne').click(function () {
        $('a.drm_delete_lien').click();
        console.log("clisk");
        return false;
    })

    $('a.drm_delete_lien').fancybox({
        autoSize: true,
        autoCenter: true,
        height: 'auto',
        width: 'auto',
        minWidth: 500,
        'afterShow': $.initProtectForms
    });
    $('.popup_contenu a#drm_delete_popup_close').click(function () {
        $.fancybox.close();
    });
};

var initFavoris = function () {
    $('div.groupe span.categorie_libelle').click(function () {
        if ($(this).hasClass('clickable')) {

            $('div.groupe span.categorie_libelle').each(function () {
                $(this).removeClass('clickable');
            });
            var id_fav_input = $(this).attr('id').replace('star_', 'drmFavoris_');
            var value = $('#colonne_intitules form #' + id_fav_input).val();
            if (value === "1") {
                $('#colonne_intitules form #' + id_fav_input).val("");
            }
            else {
                $('#colonne_intitules form #' + id_fav_input).val("1");
            }
            $("#colonne_intitules form").submit();
        }
    });
}

var initValidationDrmStockMvt = function () {
    $('fieldset.validation_drm_tables').each(function(){
      var idparent =  $(this).attr('id');
      $(this).find('li.onglet').click(function () {

        var id = $(this).attr('id').replace('_onglet', '');
        if ($(this).children().is('a')) {
            $(this).html('<span>' + $(this).html().replace('<a>', '').replace('</a>', '') + '</span>');
            $(this).addClass('actif');
            $(this).siblings().each(function () {
                $(this).html('<a>' + $(this).html().replace('<span>', '').replace('</span>', '') + '</a>');
                $(this).removeClass('actif');
            });
        }

        $('fieldset#'+idparent+' div.section_label_maj').each(function () {
            $(this).hide();
            if ($(this).attr('id') == id) {
                $(this).show();
            }
        });
        initMsgAide();
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

var initAnnexes = function () {
    $(".drm_paiement_douane_frequence input").change(function () {
        if ($(this).val() == "ANNUELLE") {
            $(".drm_paiement_douane_cumul").show();
        } else {
            $(".drm_paiement_douane_cumul").hide();
        }
    });

    genericTogglableSection("annexes");
    genericTogglableSection("apurement");
    genericTogglableSection("statistiques");
    genericTogglableSection("observations");
    genericTogglableSection("tavs");
    genericTogglableSection("informations");
}

var genericTogglableSection = function(sectionName){
  var toggleClass = ".drm_"+sectionName+"_toggle";
  var contentClass = ".drm_"+sectionName+"_content_togglable";
  $(toggleClass).click(function(){
      if($(toggleClass).find('.extendable').hasClass('ouvert')){
        $(this).find('.extendable').removeClass('ouvert');
        $(contentClass).hide();
      }else{
        $(this).find('.extendable').addClass('ouvert');
        $(contentClass).show();
    }
  });
}

var initBoldSaisie = function () {
    var pattern = '/^[a-z]*(\[[a-z]*\])(\[[a-z]*\])$/i';
    $('input.bold_on_blur').focus(function () {
        var name = $(this).attr('name');
        var matches = name.match(/^[a-z_]*\[([a-z_]+)\]\[([a-z_]+)\]$/);
        var name_header_class = matches[1] + '_' + matches[2];
        $('input.bold_on_blur').each(function () {

            if ($(this).attr('name') == name) {
                $(this).attr('style', 'font-weight:bold');

            } else {
                $(this).attr('style', 'font-weight:normal');
            }
        });
        $('span.' + name_header_class).attr('style', 'font-weight:bold')
    });
    $('input.bold_on_blur').blur(function () {
        var name = $(this).attr('name');
        var matches = name.match(/^[a-z_]*\[([a-z_]+)\]\[([a-z_]+)\]$/);
        var name_header_class = matches[1] + '_' + matches[2];
        $('span.' + name_header_class).attr('style', 'font-weight:normal')
    });
}

var initUpdateEtablissementValidation = function () {
    $('form.drm_validation_etablissement_form div.alignes ul li').click(function () {
        var caution = $('input[name=drm_validation_coordonnees_etablissement[caution]]:checked', '.drm_validation_etablissement_form').val()
        if (caution != 'DISPENSE') {
            $('div.raison_sociale_cautionneur').show();
        } else {
            $('div.raison_sociale_cautionneur').hide();
        }
    });
}

// init les tooltips dans la colonne intitules
var initMsgAide = function () {

    var msgsAide = $('.msg_aide_drm');

    msgsAide.tooltip
            ({
                placement: 'auto right',
                html: true
            });

    msgsAide.click(function () {
        return false;
    });
};

var initSaveBrouillon = function () {

    var form = $('form.hasBrouillon');

    $('.save_brouillon').click(function () {
        var action = form.attr('action') + '?brouillon=1';
        form.attr('action', action);
        form.submit();
        return false;
    });
};

$(document).ready(function ()
{
    initCreationDrmPopup();
    initDeleteDrmPopup();
    initAjoutProduitPopup();
    initAjoutCrdsPopup();
    initRegimeCrdsPopup();
    initLegalSignaturePopup();
    initCrds();
    initFavoris();
    initValidationDrmStockMvt();
    initNonApurement();
    initAnnexes();
    initUpdateEtablissementValidation();
    initSignatureDrmPopup();
    initBoldSaisie();
    initMsgAide();
    initSaveBrouillon();
});
