;(function ( $, window, undefined ) {

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

            var object = this;
            var colonnes = this.colonnes;
            this.element_saisies.find('.col_recolte').each(function(i)
            {
                var colonne = new Colonne(object, $(this));
                colonne.init();
                colonnes.push(colonne);
            });

            this.update();

            var colonne_intitule = new ColonneIntitule(this, this.element_colonne_intitules);
            colonne_intitule.init();
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

            return this.getActive() !== false;
        }

        this.focus = function() {
            this.getFocus().unfocus();
        }

        this.unfocus = function() {
        }


        this.active = function() {
            this.getActive().unactive();
            this.disabled();
        }

        this.unactive = function() {
            this.enabled();
        }

        this.updateScroll = function()
        {
            if(this.hasActive() && this.getActive().size() > 0) 
            {
                this.element_saisies.scrollTo(this.getActive(), 200);
            }
            else if(this.getFocus().size() > 0) 
            {
                this.element_saisies.scrollTo(this.getFocus(), 200);
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

    function Groupes(colonnes, element) {
        this.colonnes = colonnes;
        this.element = element;
        this.groupes = new Array();

        this.init = function() {
            var object = this;
            var groupes = this.groupes;

            element.find('.groupe').each(function() {
                var colonne_intitule_groupe = new ColonneIntituleGroupe(object, $(this));
                colonne_intitule_groupe.init();
                groupes.push(colonne_intitule_groupe);
            });
        }
    }

    function ColonneIntituleGroupe(colonne, element) {
        this.colonne = colonne;
        this.element = element;
        this.groupe_id = this.element.attr('data-groupe-id');
        this.groupes_associes = this.colonne.colonnes.element_saisies.find('.groupe[data-groupe-id='+this.groupe_id+']');
        this.titre = this.element.children('p');
        this.titres_associes = this.groupes_associes.children('p');
        this.intitules = this.element.children('ul');
        this.intitules_associes = this.groupes_associes.children('ul');


        this.init = function() {
            this.titre.add(this.titres_associes).hauteurEgale();

            var intitules_associes = this.intitules_associes;

            this.intitules.children().each(function(i)
            {
                var intitule = $(this);   
                var intitule_associe = intitules_associes.find('li:eq('+i+')');
            
                $(intitule).add(intitule_associe).hauteurEgale();
            });
        }
    }

    function ColonneGroupe(colonnes, element) {


    }

    function Colonne(colonnes, element) {

        this.colonnes = colonnes;
        this.element = element;
        this.champs = new Array();

        this.init = function() {
            this.element.append('<div class="col_masque"></div>');

            this.champs = new Champs(this);
            this.champs.init();
        }

        this.isActive = function() {

            return this.hasClass('col_active');
        }

        this.active = function() {
            this.active();
            this.focus();
            this.element.addClass('col_active');
        }

        this.unactive = function() {
            this.colonnes.unactive();
            this.element.removeClass('col_active');
           
        }

        this.enabled = function()  {
            if(this.isActive()) {
                return;
            }

            this.element.removeClass('col_inactive');
            this.champs.enabled();
        }

        this.disabled = function()  {
            if(this.isActive()) {
                return;
            }

            this.element.addClass('col_inactive');
            this.champs.disabled();
        }

        this.focus = function() {
            this.focus();
            this.element.addClass('col_focus');
            this.element.find('a.col_curseur').focus();
            this.colonnes.updateScroll();
        }

        this.unFocus = function() {
            this.colonnes.unfocus();
            this.element.removeClass('col_focus');
        }

        this.isFocus = function() {

            return this.colonnes.hasClass('col_focus');
        }

        this.reinitialiser = function() {
            if(!this.isActive()) {
                return;
            }

            this.unactive();
        }

        this.valider = function() {
            if(!this.isActive()) {
                return;
            }

            this.calculer();
            this.saving();

            var form = this.colonnes.find('form');

            $.post(form.attr('action'), form.serializeArray(), function (data)
            {
                this.unsaving();

                if(!data.success) {
                    alert("Le formulaire n'a pas été sauvegardé car il comporte des erreurs");

                    return;
                }

                this.unactive();

                this.colonnes.valider_event_function();
            });
        }

        this.saving = function() {
            this.colonnes.addClass('col_envoi');
        }

        this.unsaving = function() {
            this.colonnes.removeClass('col_envoi');
        }

        this.calculer = function() {

        }

        this._initBoutons = function () {
            this.colonnes.find('.col_btn button.btn_reinitialiser').bind('click', function() {
                    this.reinitialiser();

                    return false;
            });

            this.colonnes.find('.col_btn button.btn_valider').bind('click', function() {
                    this.valider();

                    return false;
            });
        }

    }

    function Champs(colonne) {
        this.colonne = colonne;
        this.champs = new Array();

        this.init = function() {
            var elements = this.colonne.element.find('input:text, select');

            var colonne = this.colonnes;
            var object = this;
            var champs = this.champs;

            elements.each(function(i)
            {
                champs.push(new Champ(colonne, object, $(this)));

            });

            this._init();
        }

        this.init = function () {
            for(key in this.champs) {
                champs[key].init();
            }
        }

        this.reinit = function () {
            for(key in this.champs) {
                champs[key].reinit();
            }
        }

        this.enabled = function() {
            for(key in this.champs) {
                champs[key].enabled();
            }
        }

        this.disabled = function() {
            for(key in this.champs) {
                champs[key].disabled();
            }
        }

    }

    function Champ(colonne, champs, element) {

        this.colonne = colonne;
        this.champs = champs;
        this.element = element;

        this.init = function() {
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

        this._init = function() {
            this.champs.focus(function()
            {
                if(!this.colonne.colonnes.getColonneActive() && !this.isFocus()) {
                    this.focus();
                }
            });

            this.champs.keydown(function(e)
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

            this.champs.blur(function()
            {
                if(this.colonnes.getActive()) {
                    
                    return;
                }

                var val = this.champs.val();

                if(val != val_default) { 
                    colonne.active(); 
                }
            });
                
            // Si la valeur du this.champ change alors la colonne est activée
            this.champs.change(function()
            {
                if(this.colonnes.getActive()) {
                    
                    return;
                }

                colonne.active();
            });
        }

        this._initText = function(champ) {
            if(!this.element.is('input:text'))
            {
                return;
            }

            var val_default = this.champs.attr('data-val-defaut');

            if(this.champs.attr('readonly'))
            {
                return;
            }

            this.champs.click(function(e)
            {
                this.champs.select();
                e.preventDefault();
            });
        }

        this._initNum = function(champ) {

            if(!this.element.is('input.num')) {
                
                return;
            }

            var is_float = this.champs.hasClass('num_float');
            
            this.champs.saisieNum
            (
                is_float,
                function(){ 
                    this.colonne.active(); 
                },
                function(){ 
                    this.calculer();
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