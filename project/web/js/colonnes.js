;
(function($, window, undefined) {

    var debug = false;

    /**
     Class Colonnes
     **/
    $.Colonnes = function()
    {
        this.element = $('#colonnes_dr');
        this.element_saisies = $('#col_saisies');
        this.element_saisies_container = $('#col_saisies_cont');
        this.element_colonne_intitules = $('#colonne_intitules');
        this.colonnes = new Array();
        this.groupes_rows = new GroupesRows(this);
        this.event_valider = function(colonne) {
        }
        this.event_enabled = function() {
        }
        this.event_disabled = function() {
        }
        this.event_valider_champ_modification = function() {
        }
        this.event_colonne_init = function() {
        }

        this.init = function() {
            this.colonnes = new Array();

            var colonne_intitule = new ColonneIntitule(this, this.element_colonne_intitules);
            colonne_intitule.init();
            this.colonnes.push(colonne_intitule);

            var object = this;
            var colonnes = this.colonnes;
            this.element_saisies.find('.col_recolte').each(function(i)
            {
                var colonne = new ColonneProduit(object, $(this));
                colonne.init();
                colonnes.push(colonne);
            });

            this.groupes_rows.init();

            this.update();
        }

        this.add = function(html) {
            this.element_saisies_container.append(html);
            var colonne = new ColonneProduit(this, this.element_saisies.find('.col_recolte').last());
            colonne.init();
            this.colonnes.push(colonne);
            this.update();
            colonne.unActive();
            if (colonne.isActive()) {
                colonne.active();
            }
            colonne.focus();
            colonne.focusChampDefault();

            return colonne;
        }

        this.findByHash = function(hash) {
            for (key in this.colonnes) {
                if (this.colonnes[key].element.attr('data-hash') == hash) {

                    return this.colonnes[key];
                }
            }

            return false;
        }

        this.getActive = function() {
            for (key in this.colonnes) {
                if (this.colonnes[key].isActive()) {

                    return this.colonnes[key];
                }
            }

            return false;
        }

        this.hasActive = function() {

            return this.getActive() !== false;
        }

        this.getFocus = function() {
            for (key in this.colonnes) {
                if (this.colonnes[key].isFocus()) {

                    return this.colonnes[key];
                }
            }

            return false;
        }

        this.hasFocus = function() {

            return this.getFocus() !== false;
        }

        this.unFocus = function() {
            for (key in this.colonnes) {
                if (this.colonnes[key].isFocus()) {
                    this.colonnes[key].unFocus();
                }
            }
        }

        this.unActive = function() {
            for (key in this.colonnes) {
                if (this.colonnes[key].isActive()) {
                    this.colonnes[key].unActive();
                }
            }
        }

        this.updateScroll = function()
        {
            if (this.hasActive())
            {
                this.element_saisies.scrollTo(this.getActive().element, 200);
            }
            else if (this.hasFocus())
            {
                if(this.getFocus().element.prev().length) {
                    this.element_saisies.scrollTo(this.getFocus().element.prev(), 200);
                } else {
                    this.element_saisies.scrollTo(this.getFocus().element, 200);
                }
            }
            else
            {
                this.element_saisies.scrollTo({top: 0, left: 0}, 200);
            }
        };

        this.enabled = function() {
            for (key in this.colonnes) {
                this.colonnes[key].enabled();
            }

            this.event_enabled();
        }

        this.disabled = function() {
            for (key in this.colonnes) {
                this.colonnes[key].disabled();
            }

            this.event_disabled();
        }

        this.update = function() {
            this._updateLargeur();
            this._updateHauteur();
            this.groupes_rows.update();
        }

        this._updateLargeur = function() {
            var largeur = 0;

            for (key in this.colonnes) {

                // On ajoute les largeurs de toutes les colonnes visibles sauf la première
                if(key > 0)
                {
                    largeur += this.colonnes[key].element.filter(':visible').outerWidth(true);
                }
            }

            this.element_saisies_container.width(largeur);
        }

        this._updateHauteur = function() {
            var cols = this.element_colonne_intitules.add(this.element_saisies_container);

            cols.find('.couleur, h2').hauteurEgale();
            cols.find('.label').hauteurEgale();
        }
    }

    /**
     Class ColonneIntitule
     **/
    function ColonneIntitule(colonnes, element)
    {
        this.colonnes = colonnes;
        this.element = element;
        this.groupes = new Groupes(this);

        this.init = function() {
            if (debug) {
                console.log('init colonne intitule');
                console.log(this.element);
            }

            this.groupes.init();
        }

        this.isActive = function() {

            return false;
        }

        this.isFocus = function() {

            return false;
        }

        this.enabled = function() {

            return false;
        }

        this.disabled = function() {

            return false;
        }

        this.getClass = function() {

            return 'ColonneIntitule';
        }
    }

    /**
     Class ColonneProduit
     **/
    function ColonneProduit(colonnes, element)
    {

        this.colonnes = colonnes;
        this.element = element;
        this.groupes = new Groupes(this);
        this.boutons_etapes = $('#btn_etape_dr a');

        this.init = function() {
            if (debug) {
                console.log('init colonne produit');
                console.log(this.element);
            }

            this._initBoutons();
            this.groupes.init();
            this.colonnes.event_colonne_init(this);
            this.calculer();
        }

        this.isActive = function() {

            return this.element.hasClass('col_active');
        }

        this.active = function() {
            if (this.isActive()) {
                return;
            }

            if (debug) {
                console.log('colonne active');
                console.log(this.element);
            }

            this.colonnes.unActive();
            this.focus();
            this.element.addClass('col_active');
            this.colonnes.disabled();

            // On désactive les boutons et champs autour de la colonne
            this.desactiveElements();
        }

        this.unActive = function() {
            if (debug) {
                console.log('colonne unactive');
                console.log(this.element);
            }

            this.element.removeClass('col_active');
            this.colonnes.enabled();

            // On réactive les boutons et champs autour de la colonne
            this.reactiveElements();
        }

        this.desactiveBoutons = function(e)
        {
            e.preventDefault();
        }

        this.desactiveElements = function() {

            // On désactive les boutons
            this.boutons_etapes.addClass('desactive');
            this.boutons_etapes.bind('click', this.desactiveBoutons);

            // On désactive le champ appellation
            $('#form_produit_declaration input[type="text"]').attr('disabled', 'disabled');
        }

        this.reactiveElements = function() {

            // On réactive les boutons
            this.boutons_etapes.removeClass('desactive');
            this.boutons_etapes.unbind('click', this.desactiveBoutons);

            // On réactive le champ appellation
            $('#form_produit_declaration input[type="text"]').removeAttr('disabled');
        }

        this.enabled = function() {
            if (debug) {
                console.log('colonne enabled');
                console.log(this.element);
            }

            this.element.removeClass('col_inactive');
            this.groupes.enabled();
        }

        this.disabled = function() {
            if (this.isActive()) {
                return;
            }

            this.element.addClass('col_inactive');
            this.groupes.disabled();
        }

        this.focus = function() {

            var id = this.element.attr('data-hash');

            if (this.isFocus()) {
                return;
            }

            if (debug) {
                console.log('colonne focus');
                console.log(this.element);
            }

            this.colonnes.unFocus();

            $('.drm_fil_edition_produit li[id="' + id + '"]')
            .addClass('current')
            .siblings('li')
            .removeClass('current');

            this.element.addClass('col_focus');
            this.colonnes.updateScroll();
        }

        this.focusChampDefault = function() {
            this.element.find(this.element.attr('data-input-focus')).focus();
        }

        this.unFocus = function() {
            this.element.removeClass('col_focus');
        }

        this.isFocus = function() {

            return this.element.hasClass('col_focus');
        }

        this.reinit = function() {
            if (!this.isActive()) {
                return;
            }

            if (debug) {
                console.log('colonne reinit');
                console.log(this.element);
            }

            this.groupes.reinit();
            this.unActive();
        }

        this.valider = function() {
            if (!this.isActive()) {
                return;
            }

            if (debug) {
                console.log('colonne valider');
                console.log(this.element);
            }

            this.calculer();
            this.saving();

            var object = this;

            var form = this.element.find('form');

            $.post(form.attr('action'), form.serializeArray(), function(data)
            {
                object.unSaving();

                if (!data.success) {
                    alert("Le formulaire n'a pas été sauvegardé car il comporte des erreurs");

                    return data.content;
                }

                object.groupes.valider();
                object.unActive();

                object.colonnes.event_valider(object);
            }, 'json');
        }

        this.saving = function() {
            this.element.addClass('col_envoi');
        }

        this.unSaving = function() {
            this.element.removeClass('col_envoi');
        }

        this.calculer = function() {
            if (debug) {
                console.log('colonne calculer');
                console.log(this.element);
            }
            this.groupes.calculer();
        }

        this.total = function() {

            return this.groupes.total();
        }

        this.getHash = function() {

            return this.element.attr('data-hash');
        }

        this.getClass = function() {

            return 'ColonneProduit';
        }

        this._initBoutons = function() {
            var object = this;

            this.element.find('.col_btn button.btn_reinitialiser').click(function() {
                object.reinit();
                return false;
            });

            this.element.find('.col_btn button.btn_valider').click(function() {
                object.valider();
                return false;
            });
        }
    }

    /**
     Class GroupesRows
     **/
    function GroupesRows(colonnes)
    {
        this.colonnes = colonnes;
        this.groupes_rows = new Array();

        this.init = function() {
            var object = this;
            colonnes.element_colonne_intitules.find('.groupe').each(function() {
                var groupe_id = $(this).attr('data-groupe-id');
                var groupes_row = new GroupesRow(object, groupe_id);
                groupes_row.init();
                object.groupes_rows[groupe_id] = groupes_row;
            });
        }

        this.close = function() {
            for (key in this.groupes_rows) {
                this.groupes_rows[key].close();
            }
        }

        this.update = function() {
            for (key in this.groupes_rows) {
                this.groupes_rows[key].update();
            }
            this._updateHauteur();
        }

        this._updateHauteur = function() {
            var colonnes = this.colonnes;
            if (debug) {
                console.log('hauteur egale li');
            }
            colonnes.element_colonne_intitules.find('.groupe').each(function() {
                var groupe_intitule_ul_li = $(this).find('ul li');
                var groupe_id = $(this).attr('data-groupe-id');
                var groupe_produits = colonnes.element_saisies.find('.groupe[data-groupe-id=' + groupe_id + ']');

                groupe_intitule_ul_li.each(function(i) {
                    var intitule_li = $(this);
                    var produits_li = groupe_produits.find('li:eq(' + i + ')');
                    if (produits_li.length > 0) {
                        intitule_li.add(produits_li).hauteurEgale();
                    }
                });

            });
        }
    }

    /**
     Class GroupesRow
     **/
    function GroupesRow(groupes_rows, groupe_id)
    {
        this.groupes_rows = groupes_rows;
        this.groupe_id = groupe_id;
        this.groupes_row = new Array();
        this.groupe_intitule = null;

        this.init = function() {
            this._getGroupeRows();
        }

        this._updatePosition = function() {
            if (this.isOpen()) {
                for (key in this.groupes_row) {
                    this.groupes_row[key].open();
                }

                return;
            }

            if (this.isClosed()) {
                for (key in this.groupes_row) {
                    this.groupes_row[key].close();
                }

                return;
            }
        }

        this.update = function() {
            this._getGroupeRows();
            this._updateHauteur();
            this._updatePosition();
        }

        this._getGroupeRows = function() {
            this.groupes_row = new Array();

            for (key_colonne in this.groupes_rows.colonnes.colonnes) {
                for (key_groupe in this.groupes_rows.colonnes.colonnes[key_colonne].groupes.groupes) {
                    groupe = this.groupes_rows.colonnes.colonnes[key_colonne].groupes.groupes[key_groupe];
                    if (groupe.groupe_id == this.groupe_id) {
                        if (groupe.colonne.getClass() == 'ColonneIntitule') {
                            this.groupe_intitule = groupe;
                        }
                        this.groupes_row.push(groupe);
                    }
                }
            }
        }

        this._updateHauteur = function() {
            var element = $('');
            if (debug) {
                console.log('hauteur egale p');
            }
            for (key in this.groupes_row) {
                var groupe_element = this.groupes_row[key].element;
                element.add(groupe_element.children('p'));
            }
            element.hauteurEgale();
        }

        this.isClosed = function() {

            return this.groupe_intitule.isClosed();
        }

        this.isBloque = function() {

            return this.groupe_intitule.isBloque();
        }

        this.isOpen = function() {

            return this.groupe_intitule.isOpen();
        }

        this.open = function() {
            if (this.isBloque()) {

                return;
            }

            if (debug) {
                console.log('groupes row open');
                console.log(this);
            }

            this.groupes_rows.close();
            for (key in this.groupes_row) {
                this.groupes_row[key].open();
            }
        }

        this.close = function() {
            if (this.isBloque()) {

                return;
            }

            if (debug) {
                console.log('groupes row open');
                console.log(this);
            }
            for (key in this.groupes_row) {
                this.groupes_row[key].close();
            }
        }
    }

    /**
     Class Groupes
     **/
    function Groupes(colonne)
    {
        this.colonne = colonne;
        this.groupes = new Array();

        this.init = function() {
            var elements = this.colonne.element.find('.groupe');

            var colonne = this.colonne;
            var object = this;
            var groupes = this.groupes;

            elements.each(function(i)
            {
                if (colonne.getClass() == "ColonneIntitule") {
                    var groupe = new GroupeIntitule(colonne, object, $(this));
                }

                if (colonne.getClass() == "ColonneProduit") {
                    var groupe = new GroupeProduit(colonne, object, $(this));
                }

                groupe.init();
                groupes[groupe.groupe_id] = groupe;

            });
        }

        this.valider = function() {
            for (key in this.groupes) {
                this.groupes[key].valider();
            }
        }

        this.reinit = function() {
            for (key in this.groupes) {
                this.groupes[key].reinit();
            }
        }

        this.enabled = function() {
            for (key in this.groupes) {
                this.groupes[key].enabled();
            }
        }

        this.disabled = function() {
            for (key in this.groupes) {
                this.groupes[key].disabled();
            }
        }

        this.calculer = function() {
            for (key in this.groupes) {
                this.groupes[key].calculer();
            }
        }

        this.total = function() {
            var somme = 0;
            for (key in this.groupes) {
                somme += this.groupes[key].total();
            }

            return somme;
        }
    }

    /**
     Class GroupeIntitule
     **/
    function GroupeIntitule(colonne, groupes, element)
    {
        this.colonne = colonne;
        this.groupes = groupes;
        this.element = element;
        this.element_titre = this.element.children('p');
        ;
        this.groupe_id = this.element.attr('data-groupe-id');

        this.init = function() {
            if (debug) {
                console.log('init groupe intitule');
                console.log(this.element);
            }

            this._init();
        }

        this._init = function() {
            var object = this;

            this.element_titre.click(function() {
                var groupes_row = object.getGroupesRow();
                if (groupes_row.isOpen()) {
                    groupes_row.close();
                } else {
                    groupes_row.open();
                }
            });
        }

        this.getGroupesRow = function() {

            return this.colonne.colonnes.groupes_rows.groupes_rows[this.groupe_id];
        }

        this.isOpen = function() {

            return this.element.hasClass('groupe_ouvert');
        }

        this.isClosed = function() {

            return !this.isOpen();
        }

        this.isBloque = function() {

            return this.element.hasClass('groupe_bloque');
        }

        this.open = function() {
            this.element.addClass('groupe_ouvert');
            this.element.children('ul').slideDown(function()
            {
                $(this).css('overflow', 'visible');
            });
        }

        this.close = function() {
            this.element.removeClass('groupe_ouvert');
            this.element.children('ul').css('overflow', 'hidden').slideUp();
        }
    }

    /**
     Class GroupeProduit
     **/
    function GroupeProduit(colonne, groupes, element)
    {
        this.colonne = colonne;
        this.groupes = groupes;
        this.element = element;
        this.groupe_id = this.element.attr('data-groupe-id');
        this.champs = new Champs(this.colonne, this);

        this.init = function() {
            if (debug) {
                console.log('init groupe produit');
                console.log(this.element);
            }
            this.champs.init();
        }

        this.getPrecedent = function() {
            if (this.isFirst()) {

                return false;
            }

            return this.groupes.groupes[this.groupe_id - 1];
        }

        this.getSuivant = function() {
            if (this.isLast()) {

                return false;
            }

            return this.groupes.groupes[this.groupe_id + 1];
        }

        this.isFirst = function() {

            return this.groupe_id == 1;
        }

        this.isLast = function() {

            return this.groupes.groupes.length == this.groupe_id;
        }

        this.reinit = function() {
            this.champs.reinit();
        }

        this.valider = function() {
            this.champs.valider();
        }

        this.calculer = function() {
            this.champs.calculer();
        }

        this.total = function() {

            return this.champs.total();
        }

        this.enabled = function() {
            this.champs.enabled();
        }

        this.disabled = function() {
            this.champs.disabled();
        }

        this.getGroupesRow = function() {

            return this.colonne.colonnes.groupes_rows.groupes_rows[this.groupe_id];
        }

        this.open = function() {
            this.element.children('ul').slideDown();
        }

        this.close = function() {
            this.element.children('ul').slideUp();
        }
    }

    /**
     Class Champs
     **/
    function Champs(colonne, groupe)
    {
        this.colonne = colonne;
        this.groupe = groupe;
        this.champs = new Array();

        this.init = function() {
            var elements = this.groupe.element.find('input:text, select');

            var colonne = this.colonne;
            var object = this;
            var champs = this.champs;

            elements.each(function(i)
            {
                var champ = new Champ(colonne, object, $(this));
                champ.init();
                champs.push(champ);

            });
        }

        this.valider = function() {
            for (key in this.champs) {
                this.champs[key].valider();
            }
        }

        this.reinit = function() {
            for (key in this.champs) {
                this.champs[key].reinit();
            }
        }

        this.enabled = function() {
            for (key in this.champs) {
                this.champs[key].enabled();
            }
        }

        this.disabled = function() {
            for (key in this.champs) {
                this.champs[key].disabled();
            }
        }

        this.calculer = function() {
            for (key in this.champs) {
                this.champs[key].calculer();
            }
        }

        this.somme = function() {
            var somme = 0;
            for (key in this.champs) {
                somme += this.champs[key].somme();
            }

            return somme;
        }

        this.total = function() {
            var somme = 0;
            for (key in this.champs) {
                somme += this.champs[key].total();
            }

            return somme;
        }

        this.getFirst = function() {

            return this.champs[0];
        }

        this.getLast = function() {

            return this.champs[this.champs.length - 1];
        }
    }

    /**
     Class Champ
     **/
    function Champ(colonne, champs, element)
    {

        this.colonne = colonne;
        this.champs = champs;
        this.element = element;

        this.init = function() {

            if (debug) {
                console.log('init champ');
                console.log(this.element);
            }

            this._init();
            this._initText();
            this._initSelect();
            this._initNum();
        }

        this.reinit = function() {
            this._reinitText();
            this._reinitSelect();
        }

        this.valider = function() {
            if (parseFloat(this.element.val()) != parseFloat(this.element.attr('data-val-defaut'))) {
                this.colonne.colonnes.event_valider_champ_modification(this);
            }

            this.element.attr('data-val-defaut', this.element.val());
        }

        this.enabled = function() {
            this.element.removeAttr('disabled');
        }

        this.disabled = function() {
            this.element.attr('disabled', 'disabled');
        }

        this.isNum = function() {

            return this.element.is('input.num');
        }

        this.isSomme = function() {

            return this.element.hasClass('somme_groupe');
        }

        this.isSommeDetail = function() {

            return this.element.hasClass('somme_detail');
        }

        this.isTotalDebut = function() {

            return this.element.hasClass('somme_stock_debut');
        }

        this.isTotalFin = function() {

            return this.element.hasClass('somme_stock_fin');
        }

        this.isSommeEntrees = function() {

            return this.element.hasClass('somme_entrees');
        }

        this.isSommeSorties = function() {

            return this.element.hasClass('somme_sorties');
        }

        this.getVal = function() {
            if (!this.isNum()) {

                return 0;
            }

            valeur = 0;

            if (this.element.val() != '') {
                valeur = parseFloat(this.element.val());
            }

            return valeur;
        }

        this.setVal = function(value) {
            this.element.val(value);
            this.element.nettoyageChamps();
        }

        this.calculer = function() {

            if (this.isSomme()) {
                this.setVal(this.champs.somme());

                return;
            }

            if (this.isTotalFin()) {
                var val_before = this.getVal();
                this.setVal(this.colonne.total());

                if (this.isSommeDetail() && val_before != this.getVal()) {
                    this.champs.calculer();
                }

                return;
            }

            return;
        }

        this.somme = function() {
            if (this.isSommeDetail()) {

                return this.getVal();
            }

            return 0;
        }

        this.total = function() {
            if (this.isTotalDebut()) {

                return this.getVal();
            }

            if (this.isSommeEntrees()) {

                return this.getVal();
            }

            if (this.isSommeSorties()) {

                return -this.getVal();
            }

            return 0;
        }

        this.isFirst = function() {

            return this == this.champs.getFirst()
        }

        this._init = function() {
            //     var visible = $(this.colonne.element).is(':visible');
            var colonne = this.colonne;
            var object = this;
            var groupe = this.champs.groupe;
            this.element.focus(function()
            {
                var groupes_row = groupe.getGroupesRow();
                // if (visible) {
                colonne.focus();
                //  }
                if (groupes_row.isClosed()) {
                    groupes_row.open();
                }
            });
            this.element.keydown(function(e)
            {
                if (e.keyCode == 9 && e.shiftKey)
                {

                    if (!object.isFirst() || groupe.isFirst()) {

                        return true;
                    }

                    $(this).blur();
                    groupe.getPrecedent().champs.getLast().element.focus();
                    e.preventDefault();
                }
            });
        }

        this._initSelect = function() {

            if (!this.element.is('select'))
            {
                return;
            }

            this.element.blur(function()
            {
                if (this.colonnes.hasActive()) {

                    return;
                }

                var val = this.champs.val();

                if (val != val_default) {
                    colonne.active();
                }
            });

            this.element.change(function()
            {
                if (this.colonnes.hasActive()) {

                    return;
                }

                colonne.active();
            });
        }

        this._initText = function() {

            if (!this.element.is('input:text'))
            {
                return;
            }

            if (this.element.attr('readonly'))
            {
                return;
            }

            this.element.click(function(e)
            {
                $(this).select();
                e.preventDefault();
            });
        }

        this._initNum = function() {

            if (!this.isNum()) {

                return;
            }

            var colonne = this.colonne;
            var is_float = this.element.hasClass('num_float');

            this.element.saisieNum
                    (
                            is_float,
                            function() {
                                colonne.active();
                            },
                            function() {
                                colonne.calculer();
                            }
                    );
        }

        this._reinitText = function() {
            if (!this.element.is('input:text'))
            {
                return;
            }

            this.setVal(this.element.attr('data-val-defaut'));
        }

        this._reinitSelect = function() {
            if (!this.element.is('select'))
            {
                return;
            }

            this.element.children().removeAttr('selected');
            this.element.children('[value=' + this.champ.attr('data-val-defaut') + ']').attr('selected', 'selected');
        }
    }

}(jQuery, window));
