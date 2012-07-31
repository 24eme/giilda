;(function ( $, window, undefined ) {

    var debug = true;

    $.Colonnes = function () 
    {
        this.element = $('#colonnes_dr');
        this.element_saisies = $('#col_saisies');
        this.element_saisies_container = $('#col_saisies_cont');
        this.element_colonne_intitules = $('#colonne_intitules');

        this.colonnes = new Array();
        this.valider_event_function = function () {}
        this.enabled_event_function = function () {}
        this.disabled_event_function = function () {}

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

            this.update();

           /* var colonne_intitule = new ColonneIntitule(this, this.element_colonne_intitules);
            colonne_intitule.init();*/
        }

        this.getActive = function () {
            for(key in this.colonnes) {
                if (this.colonnes[key].isActive()) {

                    return this.colonnes[key];
                }
            }

            return false;
        }

        this.hasActive = function () {

            return this.getActive() !== false;
        }

        this.getFocus = function () {
            for(key in this.colonnes) {
                if (this.colonnes[key].isFocus()) {

                    return this.colonnes[key];
                }
            }

            return false;
        }

        this.hasFocus = function () {

            return this.getFocus() !== false;
        }

        this.unFocus = function() {
            for(key in this.colonnes) {
                if (this.colonnes[key].isFocus()) {
                    this.colonnes[key].unFocus();
                }
            }
        }

        this.unActive = function() {
            for(key in this.colonnes) {
                if (this.colonnes[key].isActive()) {
                    this.colonnes[key].unActive();
                }
            }
        }

        this.updateScroll = function()
        {
            if(this.hasActive()) 
            {
                this.element_saisies.scrollTo(this.getActive().element, 200);
            }
            else if(this.hasFocus()) 
            {
                this.element_saisies.scrollTo(this.getFocus().element, 200);
            }
            else 
            {
                this.element_saisies.scrollTo({top: 0, left: 0}, 200);
            }
        };

        this.enabled = function () {
            for(key in this.colonnes) {
                this.colonnes[key].enabled();
            }

            this.enabled_event_function();
        }

        this.disabled = function() {
            for(key in this.colonnes) {
                this.colonnes[key].disabled();
            }

            this.disabled_event_function();
        }

        this.update = function() {
            var largeur = 0;
            var cols = this.element_colonne_intitules.add(this.element_saisies_container);

            for(key in this.colonnes) {
                largeur += this.colonnes[key].element.outerWidth(true);
            }

            this.element_saisies_container.width(largeur);

            cols.find('.couleur, h2').hauteurEgale();
            cols.find('.label').hauteurEgale();
        }

    }

    function ColonneIntitule(colonnes, element) {
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
    }

    function ColonneProduit(colonnes, element) {

        this.colonnes = colonnes;
        this.element = element;
        this.groupes = new Groupes(this);

        this.init = function() {
            if (debug) {
                console.log('init colonne produit');
                console.log(this.element);
            }

            this._initBoutons();
            this.groupes.init();
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
        }

        this.unActive = function() {
            this.element.removeClass('col_active');
            this.colonnes.enabled();
        }

        this.enabled = function()  {
            if(!this.isActive()) {
                return;
            }

            this.element.removeClass('col_inactive');
            this.groupes.enabled();
        }

        this.disabled = function()  {
            if(this.isActive()) {
                return;
            }

            this.element.addClass('col_inactive');
            this.groupes.disabled();
        }

        this.focus = function() {
            if (this.isFocus()) {
                return;
            }

            if (debug) {
                console.log('colonne focus');
                console.log(this.element);
            }

            this.colonnes.unFocus();
            this.element.addClass('col_focus');
            //this.element.find('a.col_curseur').focus();
            this.colonnes.updateScroll();
        }

        this.unFocus = function() {
            this.element.removeClass('col_focus');
        }

        this.isFocus = function() {

            return this.element.hasClass('col_focus');
        }

        this.reinit = function() {
            if(!this.isActive()) {
                return;
            }

            if (debug) {
                console.log('colonne reinit');
                console.log(this.element);
            }

            this.unActive();
            this.groupes.reinit();
        }

        this.valider = function() {
            if(!this.isActive()) {
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

            $.post(form.attr('action'), form.serializeArray(), function (data)
            {
                object.unSaving();

                if(!data.success) {
                    alert("Le formulaire n'a pas été sauvegardé car il comporte des erreurs");

                    return;
                }

                object.unActive();

                object.colonnes.valider_event_function();
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

        this._initBoutons = function () {
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

    function GroupeRows(colonne, groupe_id) {
        this.groupe_intitule = groupe_intitule;
        this.colonne = colonne;
        this.groupe_id = groupe_id;
        this.groupes = new Array();

        this.init = function() {
            this.update();
        }

        this.open = function() {
            for(key in this.groupes) {
                this.groupes[key].open();
            }
        }

        this.close = function() {
            for(key in this.groupes) {
                this.groupes[key].close();
            }
        }

        this.update = function() {
            this.groupes = this._getGroupes();
        }

        this._getGroupes = function() {
            var groupes = new Array();
            for(key in this.colonne.groupes) {
                if(this.colonne.groupes[key] == this.groupe_id) {
                    groupes.push(this.colonne.groupes[key]);
                }
            }

            return groupes;
        }
    }

    function Groupes(colonne) {
        this.colonne = colonne;
        this.groupes = new Array();

        this.init = function() {
            var elements = this.colonne.element.find('.groupe');

            var colonne = this.colonne;
            var object = this;
            var groupes = this.groupes;

            elements.each(function(i)
            {
                var groupe = new Groupe(colonne, object, $(this));
                groupe.init();
                groupes[groupe.groupe_id] = groupe;

            });
        }

        this.reinit = function () {
            for(key in this.groupes) {
                this.groupes[key].reinit();
            }
        }

        this.enabled = function() {
            for(key in this.groupes) {
                this.groupes[key].enabled();
            }
        }

        this.disabled = function() {
            for(key in this.groupes) {
                this.groupes[key].disabled();
            }
        }

        this.calculer = function() {
            for (key in this.groupes) {
                this.groupes[key].calculer();
            }
        }
    }

    function Groupe(colonne, groupes, element) {


    }

    function Groupe(colonne, groupes, element) {
        this.colonne = colonne;
        this.groupes = groupes;
        this.element = element;
        this.groupe_id = this.element.attr('data-groupe-id');
        this.champs = new Champs(this.colonne, this);
        
        this.init = function() {
            if (debug) {
                console.log('init groupe');
                console.log(this.element);
            }
            this.champs.init();
        }

        this.calculer = function() {
            this.champs.calculer();
        }

        this.reinit = function () {
            this.champs.reinit();
        }

        this.enabled = function() {
            this.champs.enabled();
        }

        this.disabled = function() {
            this.champs.disabled();
        }

        this.open = function() {
            this.element.slideDown();
        }

        this.close = function() {
            this.element.slideUp();
        }
    }

    function Champs(colonne, groupe) {
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

        this.reinit = function () {
            for(key in this.champs) {
                this.champs[key].reinit();
            }
        }

        this.enabled = function() {
            for(key in this.champs) {
                this.champs[key].enabled();
            }
        }

        this.disabled = function() {
            for(key in this.champs) {
                this.champs[key].disabled();
            }
        }

        this.calculer = function() {
            for(key in this.champs) {
                this.champs[key].calculer();
            }
        }

        this.somme = function() {
            somme = 0;
            for(key in this.champs) {
                if(this.champs[key].isDetailSomme()) {
                    somme += this.champs[key].getVal();
                }
            }

            return somme;
        }
    }

    function Champ(colonne, champs, element) {

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

        this.reinit = function()  {
            this._reinitText();
            this._reinitSelect();
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

        this.isDetailSomme = function() {

            return this.element.hasClass('somme_detail');
        }

        this.getVal = function() {
            if(!this.isNum()) {

                return 0; 
            }

            valeur = 0;

            if(this.element.val() != '') {
                valeur = parseFloat(this.element.val());
            }

            return valeur;
        }

        this.setVal = function(value) {
            if(!this.isNum()) {

                return;
            }

            return this.element.val(value);
        }

        this.calculer = function() {
            if(!this.isSomme()) {
                
                return;
            }

            this.setVal(this.champs.somme());
        }

        this._init = function() {
            var colonne = this.colonne;

            this.element.focus(function()
            {
                colonne.focus();
            });

            this.element.keydown(function(e)
            {
                if(e.keyCode == 9 && e.shiftKey)
                {
                    // Si le champ courant est le 1er d'un groupe
                    // Et s'il y a un groupe précédent 
                    /*if(groupePrec.exists() && champPremier)
                    {
                        this.champs.blur();
                        
                        // Si le groupe n'était pas ouvert ni bloqué au démarrage
                        if(!groupe.hasClass('bloque') && !groupe.hasClass('demarrage-ouvert') )
                        {
                            groupe.trigger('fermer');
                        }
                        
                        groupePrec.trigger('ouvrir');
                        champDernierGroupePrec.focus();
                        
                        e.preventDefault();
                    }*/
                }
            });
        }

        this._initSelect = function() {

            if(!this.element.is('select'))
            {
                return;
            }

            this.element.blur(function()
            {
                if(this.colonnes.getActive()) {
                    
                    return;
                }

                var val = this.champs.val();

                if(val != val_default) { 
                    colonne.active(); 
                }
            });
                
            this.element.change(function()
            {
                if(this.colonnes.getActive()) {
                    
                    return;
                }

                colonne.active();
            });
        }

        this._initText = function() {

            var element = this.element;

            if(!this.element.is('input:text'))
            {
                return;
            }

            var val_default = this.element.attr('data-val-defaut');

            if(this.element.attr('readonly'))
            {
                return;
            }

            this.element.click(function(e)
            {
                element.select();
                e.preventDefault();
            });
        }

        this._initNum = function() {

            if(!this.isNum()) {
                
                return;
            }

            var colonne = this.colonne;

            var is_float = this.element.hasClass('num_float');
            
            this.element.saisieNum
            (
                is_float,
                function(){
                    colonne.active(); 
                },
                function(){
                    colonne.calculer();
                }
            );
        }

        this._reinitText = function() {
            if(!this.element.is('input:text'))
            {
                return;
            }

            this.element.val(champ.attr('data-val-defaut'));
        }

        this._reinitSelect = function() {
            if(!this.element.is('select'))
            {
                return;
            }

            this.element.children().removeAttr('selected');
            this.element.children('[value='+this.champ.attr('data-val-defaut')+']').attr('selected', 'selected');
        }
    }

}(jQuery, window));