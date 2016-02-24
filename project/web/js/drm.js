/**
 * Fichier : déclaration.js
 * Description : fonctions JS spécifiques à la déclaration
 * Auteur : Hamza Iqbal - hiqbal[at]actualys.com
 * Copyright: Actualys
 ******************************************/

/**
 * Initialisation
 ******************************************/

(function ($)
{
    var anchorIds = {"entrees": 2, "sorties": 3};
    // Variables globales

    var selectProduit = $('#produit_declaration_hashref');

    var colonnesDR = $('#colonnes_dr');
    var colonnes;

    $(document).ready(function ()
    {

        //$('.autocomplete').combobox();


        $(".stickyHeader").sticky({topSpacing: 0});

        if (colonnesDR.length)
        {
            colonnes = new $.Colonnes();
            colonnes.init();

            colonnes.event_valider = function (colonne, nextfocus) {
                $('#list-produits a[data-hash="' + colonne.getHash() + '"]').addClass('list-group-item-success');
                if ((nextfocus == undefined) || (nextfocus == 'nextCol')) {
                    var next = colonne.getNext();
                    if (next) {
                        next.focus();
                        next.focusChampDefault();
                    } else {
                        $('form button.btn-success').focus();
                    }
                } else {
                    if (nextfocus == 'produits') {
                        var select2Produit = $("#s2id_produit_declaration_hashref").data('select2');
                        setTimeout(function () {
                            if (!select2Produit.opened()) {
                                select2Produit.open();
                            }
                        }, 0);
                    }
                    if (nextfocus == 'prevCol') {
                        var prev = colonne.gePrevious();
                        if (prev) {
                            prev.focus();
                            prev.focusChampDefault();
                        }
                    }else{
                        var colToFocus = colonnes.findByHash(nextfocus);
                        colToFocus.focus();
                        colToFocus.focusChampDefault();
                    }
                }
            }

            colonnes.event_focus = function (colonne) {
                $('#list-produits a[data-hash="' + colonne.getHash() + '"]').addClass('active');

                $('#list-produits a[data-hash="' + colonne.getHash() + '"] span').attr('style', "position:absolute; right: 1px; top:5px; color:#fff;");
            }

            colonnes.event_unfocus = function (colonne) {
                $('#list-produits a').removeClass('active');
                $('#list-produits a span').attr('style', 'position:absolute; right: 1px; top:5px;');

            }

            colonnes.event_disabled = function (colonne) {
                $('#list-produits a[data-hash="' + colonne.getHash() + '"]').addClass('disabled');
            }

            colonnes.event_enabled = function (colonne) {
                $('#list-produits a[data-hash="' + colonne.getHash() + '"]').removeClass('disabled');
            }

            colonnes.event_edition_on = function (colonne) {
                $('#form_produit_declaration select, #navigation_etapes button, #navigation_etapes a').attr('disabled', 'disabled');
            }

            colonnes.event_edition_off = function (colonne) {
                $('#form_produit_declaration select, #navigation_etapes button, #navigation_etapes a').removeAttr('disabled');
            }

            $.initProduitForm();

            if (colonnes.colonnes.length > 1) {
                var colonne = colonnes.colonnes[1];
                colonne.focus();
                colonne.focusChampDefault();
            }

            $('.drm_input_details').on('modal_callback', function (e, data) {
                $(this).val(data.volume);
                $(this).change();

                if ($(this).data('hash')) {
                    colonnes.findByHash($(this).data('hash')).calculer();
                }
            });
            initRaccourcis();
        }

        initCreationDrmPopup();
        initRegimeCrdsPopup();
        initCrds();
        initFavoris();
        initNonApurement();
        initUpdateEtablissementValidation();
        initSignatureDrmPopup();
        initBoldSaisie();
    });

    var initRaccourcis = function () {
        $('li.no_favoris a.raccourcis_ouvrir').each(function () {
            $(this).keypress(function (e) {
                var idCol = $(this).data('groupe-id');
                if (e.keyCode === 0 || e.keyCode === 32) {
                    e.preventDefault();
                    $('div.no_favoris[data-groupe-id="' + idCol + '"] > p').trigger('click');
                }
            });
            $(this).click(function () {
                var idCol = $(this).data('groupe-id');
                $('div.no_favoris[data-groupe-id="' + idCol + '"] > p').trigger('click');
            });
        });

        $('input.tabIndexOnPrevious').keydown(function (e) {
            var event = window.event || e;
            var keyCode = event.keyCode || event.which;
            if (keyCode == 9 && event.shiftKey) {
                var datapreviousfocus = $(this).data('previousfocus');
                $('a[tabindex=' + datapreviousfocus + ']').focus();
                return false;
            }
        });
        $('input.somme_detail').keydown(function (e) {
            var event = window.event || e;
            var keyCode = event.keyCode || event.which;
            if (event.ctrlKey) {
                if (keyCode == 38) {
                    var col = colonnes.findByHash($('.col_focus').data('hash'));
                    col.valider('produits');
                    return false;
                }
                if (keyCode == 39) {
                    var col = colonnes.findByHash($('.col_focus').data('hash'));
                    col.valider('nextCol');
                    return false;
                }
                if (keyCode == 37) {
                    var col = colonnes.findByHash($('.col_focus').data('hash'));
                    col.valider('prevCol');
                    return false;
                }
            }
        });
        $('div.panel-heading').click(function(){
            var col = colonnes.findByHash($('.col_focus').data('hash'));     
            col.valider($(this).parent().data('hash')); 
        });
    }

    var initSignatureDrmPopup = function () {


        $('#signature_drm_popup_content a#signature_drm_popup_close').click(function () {
            //$.fancybox.close();
        })

        $('#signature_drm_popup_content a#signature_drm_popup_confirm').click(function () {
            $("form#drm_validation input#drm_email_transmission").val($('#drm_email_transmission_visible').val());
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

        /*$('a.ajout_crds_popup').fancybox({
         autoSize: true,
         autoCenter: true,
         height: 'auto',
         width: 'auto',
         'afterShow': openedPopupAjoutCRD
         
         });*/

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

    var initRegimeCrdsPopup = function () {
        /*$('a.crd_regime_choice_popup').fancybox({
         autoSize: true,
         autoCenter: true,
         height: 'auto',
         width: 'auto',
         closeClick: false,
         closeBtn: false,
         helpers: {
         overlay: {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
         }
         });*/
        $('a.crd_regime_choice_popup').click();

    };

    var initCreationDrmPopup = function () {

        /*$('a.drm_nouvelle_teledeclaration').fancybox({
         autoSize: true,
         autoCenter: true,
         height: 'auto',
         width: 'auto',
         minWidth: 500,
         'afterShow': $.initProtectForms
         
         });*/

        $('.popup_contenu a#drm_nouvelle_popup_close').click(function () {
            //$.fancybox.close();
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

    var initFavoris = function () {
        $('form#colonne_intitules').find('span.categorie_libelle').each(function () {
            $(this).parent().click(function () {

                var id_fav_input = $(this).find('.categorie_libelle').attr('id').replace('star_', 'drmFavoris_');
                var value = $('#colonne_intitules input#' + id_fav_input).val();
                if (value === "1") {
                    $('#colonne_intitules input#' + id_fav_input).val("");
                } else {
                    $('#colonne_intitules input#' + id_fav_input).val("1");
                }
                $("form#colonne_intitules").submit();
            });
        });
    }

    var initCollectionNonApurementTemplate = function (element, regexp_replace, callback)
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
        $('.drm_non_apurement_delete_row .btn_supprimer_ligne_template').on('click', function ()
        {
            var element = $(this).parent().parent();
            $(element).remove();
            return false;
        });
    }

    var initNonApurement = function () {
        initCollectionNonApurementTemplate('.ajouter_non_apurement .btn_ajouter_ligne_template', /var---nbItem---/g, function (bloc) {});
        initCollectionDeleteNonApurementTemplate();
    }

    var initBoldSaisie = function () {
        var pattern = '/^[a-z]*(\[[a-z]*\])(\[[a-z]*\])$/i';
        $('input.bold_on_blur').focus(function () {
            var name = $(this).attr('name');
            if (!name) {
                return;
            }
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
            if (!name) {
                return;
            }
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


    /**
     * Initialise l'ajax pour le formulaire d'ajout d'un produit
     * $.initProduitForm();
     ******************************************/
    $.initProduitForm = function () {
        var formProduit = $('#form_produit_declaration');

        selectProduit.find('optgroup[label=existant]').addClass('existant');
        //selectProduit.combobox();

        selectProduit.change(function () {
            formProduit.submit();
        });

        formProduit.submit(function () {
            var selected = selectProduit.find('option:selected');
            if (!selected.parent('optgroup').hasClass('existant')) {

                return true;
            }
            colonne = colonnes.findByHash(selected.val());
            colonne.focus();
            colonne.focusChampDefault();
            selected.removeAttr('selected');
            selectProduit.parent().find('.ui-autocomplete-input').val('');

            return false;
        });

        $('#list-produits a').click(function () {
            if ($(this).hasClass('disabled')) {
                return false;
            }
            colonne = colonnes.findByHash($(this).attr('data-hash'));
            colonne.focus();
            colonne.focusChampDefault();

            return false;
        });

        $('#list-produits a span').click(function () {
            if ($(this).hasClass('disabled')) {
                return false;
            }
            colonne = colonnes.findByHash($(this).parent().attr('data-hash'));
            if ($(this).hasClass('glyphicon-eye-open')) {

                if (colonne.hide()) {
                    $(this).parent().attr('opacity', "0.5");
                    $(this).removeClass('glyphicon-eye-open');

                    $(this).addClass('glyphicon-eye-close');
                }
                // colonne.focusChampDefault();
            } else {
                colonne.show();
                $(this).parent().attr('opacity', "1");
                $(this).removeClass('glyphicon-eye-close');

                $(this).addClass('glyphicon-eye-open')
                // colonne.focusChampDefault();
            }

            return false;
        });
    }

    /**
     * Initialise l'activation d'un colonne
     ******************************************/
    $.initColActive = function ()
    {
        if (colActiveDefaut.exists()) {
            colActiveDefaut.majColActive();
        }
    };

    $.majSommeLabelBind = function ()
    {
        var vol = 0;
        $('.drm_details_tableBody td.volume').each(function ()
        {
            var vol_val = $(this).find('div input').val();
            if (vol_val == '')
                vol_val = 0;
            var vol_val_float = parseFloat(vol_val);
            if (isNaN(vol_val_float))
                return true;
            vol += vol_val_float;
        });
        $('.drm_details_volume_total').val(vol.toFixed(2));
    }


})(jQuery);