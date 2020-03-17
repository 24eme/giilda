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
    var btnAjouter = colonnesDR.find('.btn_ajouter');

    var btnEtapesDR = $('#btn_etape_dr');
    var btnPrecSuivProd = $('#btn_suiv_prec');

    var colonnes;

//
//	var actifPopupLien;

    $(document).ready(function ()
    {

        $('.autocomplete').combobox();

        if (colonnesDR.exists())
        {
            colonnes = new $.Colonnes();

            colonnes.event_colonne_init = function (colonne) {
                var btns_details = colonne.element.find("input.input_lien.drm_details");

                btns_details.click(function () {
                    input = $(this);

                    $.fancybox({type: 'ajax',
                        href: input.attr('data-href'),
                        fitToView: false,
                        beforeShow: function ()
                        {
                            $("body").css({'overflow-y': 'hidden'});
                        },
                        afterShow: function ()
                        {
                            input.initDetailsPopup(colonne);
                            $('.fancybox-outer').before($('.fancybox-title'));
                            initMsgAide();
                        },
                        onClose: function ()
                        {
                            $.unbindDetailsPopup();
                        },
                        afterClose: function ()
                        {
                            $("body").css({'overflow-y': 'visible'});
                        },
                        helpers:
                                {
                                    title: {
                                        type: 'inside'
                                    }
                                },
                        beforeLoad: function () {
                            this.title = input.attr('data-title');
                        }
                    });
                });

                $(document).on('keydown', function (e)
                {
                    // Si on appuie sur la barre d'espace
                    if (e.keyCode === 32)
                    {
                        btns_details.filter(':focus').click();
                    }
                });

                colonne.element.find("a.labels_lien").each(function () {
                    var lien = $(this);
                    lien.fancybox({type: 'ajax',
                        autoSize: false,
                        autoCenter: false,
                        height: 'auto',
                        width: 'auto',
                        afterShow: function ()
                        {
                            lien.initLabels(colonne);
                        }
                    });
                });
            };

            colonnes.event_valider = function (colonne) {
                colonne.element.addClass('col_edited');
                $('.drm_fil_edition_produit li[id="' + colonne.element.attr('data-hash') + '"]').addClass('edited');
            }

            colonnes.init();

            $.initRaccourcis();
            $.initProduitForm();
            $.initFilEditionProduit();
            $.initDetailsPopups();

            if (colonnes.colonnes.length > 1) {
                var colFocus = (!isNaN(colFocusDefaut) && colFocusDefaut != undefined) ? colFocusDefaut : 1;
                var colonne = colonnes.colonnes[colFocus];
                colonne.focus();
                colonne.focusChampDefault();
            }
        }
    });

    $.fn.initLabels = function (colonne)
    {
        var lien = $(this);
        $('.drm_labels_form').bind('submit', function ()
        {
            $.post($(this).attr('action'),
                    $(this).serialize(),
                    function (data)
                    {
                        if (!data.success)
                        {
                            $.fancybox.update();
                        }
                        else
                        {
                            lien.parent().html('<span>' + data.content + '</span><a href="' + lien.attr('href') + '" class="' + lien.attr('class') + '" title="' + lien.attr('title') + '">' + lien.html() + '</a>&nbsp;');
                            colonne.colonnes.update();
                            colonnes.event_colonne_init(colonne);
                            $.fancybox.close();
                        }
                    }, "json");

            return false;
        });
    };

    /**
     * Initialise l'ajax pour le formulaire d'ajout d'un produit
     * $.initProduitForm();
     ******************************************/
    $.initProduitForm = function () {
        var formProduit = $('#form_produit_declaration');
        var filEditionProduit = $('.drm_fil_edition_produit');

        selectProduit.find('optgroup[label=existant]').addClass('existant');
        selectProduit.combobox();

        selectProduit.change(function () {
            formProduit.submit();
        });

        formProduit.submit(function () {
            var selected = selectProduit.find('option:selected');
            var inputAutoComplete = selectProduit.parent().find('.ui-autocomplete-input');
            if (selected.parent('optgroup').hasClass('existant')) {
                colonne = colonnes.findByHash(selected.val());
                colonne.focus();
                colonne.focusChampDefault();
                $('.drm_fil_edition_produit li').removeClass("current");
                $('.drm_fil_edition_produit li[id="' + colonne.element.attr('data-hash') + '"]').addClass('current');
                selected.removeAttr('selected');
                selectProduit.parent().find('.ui-autocomplete-input').val('');

                return false;
            }
            $.post($(this).attr('action'), $(this).serializeArray(), function (data) {
                if (data.success) {
                    colonne = colonnes.add(data.content);
                    selectProduit.find('optgroup[class=existant]').append('<option value="' + data.produit.hash + '">' + data.produit.libelle + '</option>')
                    selectProduit.find('optgroup[label=nouveau]').find('option[value="' + data.produit.old_hash + '"]').remove();
                    selected.removeAttr('selected');
                    inputAutoComplete.val('');
                    filEditionProduit.append('<li id="' + data.produit.hash + '"><a href="#">' + data.produit.libelle + '</a></li>');
                    $('.drm_fil_edition_produit li').removeClass("current");
                    $('.drm_fil_edition_produit li[id="' + colonne.element.attr('data-hash') + '"]').addClass('current');
                }
            }, 'json');

            return false;
        });

    };

    $.initFilEditionProduit = function () {

        $('.drm_fil_edition_produit').on('click', 'a', function (e)
        {
            var parent = $(this).parent();
            var id = parent.attr('id');

            e.preventDefault();

            parent
                    .addClass('current')
                    .siblings('li')
                    .removeClass('current');

            colonne = colonnes.findByHash(id);
            colonne.focus();
            colonne.focusChampDefault();
        });

    };


    /**
     * Initialisation des actions associées
     * aux raccourci clavier
     * $.initRaccourcis();
     ******************************************/
    $.initRaccourcis = function (col)
    {
        // Ctrl + flèche gauche ==> Changement de focus
        /*$.ctrl(37, function() {$.majColFocus('prec');});

         // Ctrl + flèche droite ==> Changement de focus
         $.ctrl(39, function() {$.majColFocus('suiv');});*/

        // Ctrl + M ==> Commencer édition colonne avec focus
        // $.ctrl(77, function () {colFocus.majColActive(true);});

        // Ctrl + P ==> Commencer édition colonne avec focus
        $.ctrl(80, function () {
            selectProduit.parent().find('.ui-autocomplete-input').focus();
        });

        // Ctrl + touche supprimer ==> Suppression colonne avec focus
        //$.ctrl(46, function() { colFocus.find('.btn_supprimer').trigger('click'); });

        // Echap ==> Réinitialisation de la colonne active
        $.echap(function () {
            if (colonnes.hasActive()) {
                colonnes.getActive().reinit();
            }
        });

        // Ctrl + Entrée ==> Validation de la colonne active
        // $.ctrl(13, function() {colFocus.find('.btn_valider').trigger('click');});
    };

    /**
     * Initialise l'activation d'un colonne
     ******************************************/
    $.initColActive = function ()
    {
        if (colActiveDefaut.exists()) {
            colActiveDefaut.majColActive();
        }
    };


    /**
     * Gère les raccourcis clavier du type Ctrl+Touche
     * $.ctrl(key, callback, args);
     ******************************************/
    $.ctrl = function (key, callback, args)
    {
        $(document).keydown(function (e)
        {
            if (!args)
                args = [];

            if (e.keyCode == key && e.ctrlKey)
            {
                callback.apply(this, args);
                return false;
            }
        });
    };
    /**
     * Gère le raccourci clavier Echap
     * $.echap(callback, args);
     ******************************************/
    $.echap = function (callback, args)
    {
        $(document).keydown(function (e)
        {
            if (!args)
                args = [];

            if (e.keyCode == 27)
            {
                callback.apply(this, args);
                return false;
            }
        });
    };

    /**
     * Gère les raccourcis clavier du type Shift+Touche
     * $.shift(key, callback, args);
     ******************************************/
    $.shift = function (key, callback, args)
    {
        $(document).keydown(function (e)
        {
            if (!args)
                args = [];

            if (e.keyCode == key && e.shiftKey)
            {
                callback.apply(this, args);
                return false;
            }
        });
    };



    /**
     * Contrôle la bonne saisie de nombres dans
     * un champ
     * $(s).saisieNum(float, callbackKeypress);
     ******************************************/
    $.fn.saisieNum = function (float, callbackKeypress, callbackBlur)
    {
        var champ = $(this);

        // A chaque touche pressée
        champ.keypress(function (e)
        {
            var val = $(this).val();
            var touche = e.which;
            var ponctuationPresente = (val.indexOf('.') != -1 || val.indexOf(',') != -1);
            var chiffre = (touche >= 48 && touche <= 57); // Si chiffre

            // touche "entrer"
            if (touche == 13)
                return e;

            // touche "entrer"
            if (touche == 0)
                return e;

            // Champ nombre décimal
            if (float)
            {
                // !backspace && !null && !point && !virgule && !chiffre
                if (touche != 8 && touche != 0 && touche != 46 && touche != 44 && !chiffre)
                    return false;
                // point déjà présent
                if (touche == 46 && ponctuationPresente)
                    e.preventDefault();
                // virgule déjà présente
                if (touche == 44 && ponctuationPresente)
                    e.preventDefault();
                // 5 décimales
                if (val.match(/[\.\,][0-9][0-9][0-9][0-9][0-9]/) && chiffre && e.currentTarget && e.currentTarget.selectionStart > val.length - 3)
                    e.preventDefault();
            }
            // Champ nombre entier
            else
            {
                if (touche != 8 && touche != 0 && !chiffre)
                    e.preventDefault();
            }

            if (callbackKeypress)
                callbackKeypress();
            return e;
        });

        // A chaque touche pressée
        champ.keyup(function (e)
        {
            var touche = e.which;

            // touche "retour"
            if (touche == 8)
            {
                if (callbackKeypress)
                    callbackKeypress();
                return e;
            }
        });


        // A chaque fois que l'on quitte le champ
        champ.blur(function ()
        {
            $(this).nettoyageChamps();
            if (callbackBlur)
                callbackBlur();
        });
    };


    /**
     * Nettoie les champs après la saisie
     * $(champ).nettoyageChamps();
     ******************************************/
    $.fn.nettoyageChamps = function ()
    {
        var champ = $(this);
        var val = champ.attr('value');
        var float = champ.hasClass('num_float');
        var champ_int = champ.hasClass('num_int');

        // Si quelque chose a été saisi
        if (val)
        {
            // Remplacement de toutes les virgules par des points
            if (val.indexOf(',') != -1)
                val = val.replace(',', '.');

            // Si un point a été saisi sans chiffre
            if (val.indexOf('.') != -1 && val.length == 1)
                val = ''; //val = '0';

            // Un nombre commençant par 0 peut être interprété comme étant en octal
            if (val.indexOf('0') == 0 && val.length > 1)
                val = val.substring(1);

            // Comparaison nombre entier / flottant
            if (float || parseInt(val) != parseFloat(val) && !champ_int){
                decimales = val.slice(val.indexOf('.')+1);
                var nbDecimal = (decimales.length < 2 )? 2 : decimales.length;
                if(nbDecimal > 5) nbDecimal = 5;
                val = parseFloat(val).toFixed(nbDecimal);

            } else {
                val = parseInt(val);
            }
        }
        // Si rien n'a été saisi
        //else val = 0;
        else
            val = '';

        // Si ce n'est pas un nombre (ex : copier/coller d'un texte)
        if (isNaN(val))
            val = ''; //val = 0;

        /*if (val == 0) {
         champ.addClass('num_light');
         } else {
         champ.removeClass('num_light');
         }*/
        champ.attr('value', val);
    };

    $.fn.nettoyageChampsWithFourPrecision = function ()
    {
        var champ = $(this);
        var val = champ.attr('value');
        var float = champ.hasClass('num_float');

        // Si quelque chose a été saisi
        if (val)
        {
            // Remplacement de toutes les virgules par des points
            if (val.indexOf(',') != -1)
                val = val.replace(',', '.');

            // Si un point a été saisi sans chiffre
            if (val.indexOf('.') != -1 && val.length == 1)
                val = ''; //val = '0';

            // Un nombre commençant par 0 peut être interprété comme étant en octal
            if (val.indexOf('0') == 0 && val.length > 1)
                val = val.substring(1);

            // Comparaison nombre entier / flottant
            if (float || parseInt(val) != parseFloat(val))
                val = parseFloat(val).toFixed(5);
            else
                val = parseInt(val);
        }
        // Si rien n'a été saisi
        //else val = 0;
        else
            val = '';

        // Si ce n'est pas un nombre (ex : copier/coller d'un texte)
        if (isNaN(val))
            val = ''; //val = 0;

        /*if (val == 0) {
         champ.addClass('num_light');
         } else {
         champ.removeClass('num_light');
         }*/
        champ.attr('value', val);
    };


    /**
     * Initialisation des Popups des détails
     * $.initDetailsPopups();
     ******************************************/
    $.initDetailsPopups = function ()
    {
        $('.drm_details_form .drm_details_remove').live('click', function ()
        {
            $(this).parent().parent().remove();
            var lignes = $('.drm_details_tableBody tr');

            if (lignes.length <= 1) {
                $('.drm_details_addTemplate').trigger('click');
            }
            $.fancybox.update();
            $.majSommeLabelBind();
        });

        $('.drm_details_annuler').live('click', function ()
        {
            $.fancybox.close();
            return false;
        });

    };

    $.unbindDetailsPopup = function ()
    {
        $('.drm_details_addTemplate').unbind();
        $('.drm_details_remove').unbind();
        $('.drm_details_form').unbind();
    };

    $.bindAddTemplateLien = function ()
    {
        $('.drm_details_addTemplate').bind('click', function ()
        {
            var content = $($('.template_details').html().replace(/var---nbItem---/g, UUID.generate()));
            $('.drm_details_tableBody tr:last').before(content);
            $('.autocomplete').combobox();
            $('.champ_datepicker input').initDatepicker();
            $.majSommeLabel();
            $.fancybox.update();
        });
    }

    $.fn.initDetailsPopup = function (colonne) {

        var input = $(this);

        $('.autocomplete').combobox();
        $('.champ_datepicker input').initDatepicker();
        $.majSommeLabel();
        $.bindAddTemplateLien();

        $('.drm_details_form').bind('submit', function ()
        {
            $(this).children('button[type="submit"]').attr('disabled','disabled');
            $.post($(this).attr('action'),
                    $(this).serialize(),
                    function (data)
                    {
                        if (!data.success)
                        {
                            $('.drm_details_form_content').html(data.content);
                            $('.autocomplete').combobox();
                            $('.champ_datepicker input').initDatepicker();
                            $.majSommeLabel();
                            $.bindAddTemplateLien();
                            $.fancybox.update();
                        }
                        else
                        {
                            input.val(data.volume);
                            input.nettoyageChamps();
                            input.attr('data-val-defaut', input.val());
                            colonne.active();
                            colonne.calculer();
                            $.fancybox.close();
                            $.fn.RevisionajaxSuccessCallBackData = colonne;
                            $.fn.RevisionajaxSuccessCallBack = function () {
                                $.fn.RevisionajaxSuccessCallBackData.valider();
                            }
                        }
                    }, "json");

            return false;
        });
    };

    $.fn.initDatepicker = function ()
    {
        $(this).datepicker({
            showOn: 'both',
            buttonImage: "/images/pictos/pi_calendrier.png",
            buttonImageOnly: true,
            dayNamesMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
            monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre", "Novembre", "Décembre"],
            dateFormat: 'dd/mm/yy',
            firstDay: 1});
    };

    $.majSommeLabel = function ()
    {
        $.majSommeLabelBind = function ()
        {
            var vol = 0;
            $('.drm_details_tableBody td.volume').each(function ()
            {
                var vol_val = $(this).children('input').val();
                if (vol_val == '')
                    vol_val = 0;
                var vol_val_float = parseFloat(vol_val);
                if (isNaN(vol_val_float))
                    return true;
                vol += vol_val_float;
            });
            $('.drm_details_volume_total').text(vol.toFixed(5));
        }
        $('.drm_details_tableBody td.volume').unbind();
        $('.drm_details_tableBody td.volume').bind('keyup', $.majSommeLabelBind);
    }

})(jQuery);
