<?php

class SocieteRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();

        $r->prependRoute('societe', new sfRoute('/societe', array('module' => 'societe',
            'action' => 'index')));

        $r->prependRoute('soc_etb_com_autocomplete_all', new sfRoute('/societe-etablissement-compte/autocomplete/:interpro_id/tous', array('module' => 'societe',
            'action' => 'fullautocomplete')));

        $r->prependRoute('soc_etb_com_autocomplete_actif', new sfRoute('/societe-etablissement-compte/autocomplete/:interpro_id/actif', array('module' => 'societe',
            'action' => 'actifautocomplete')));

        $r->prependRoute('societe_autocomplete_all', new sfRoute('/societe/autocomplete/:interpro_id/:type', array('module' => 'societe',
            'action' => 'autocomplete')));

        $r->prependRoute('societe_contact_chosen', new sfRoute('/societe-etablissement-compte/:identifiant/choisi', array('module' => 'societe',
            'action' => 'contactChosen')));


        $r->prependRoute('societe_choose', new SocieteRoute('/societe/:identifiant/espace', array('module' => 'societe',
            'action' => 'monEspace'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));

        $r->prependRoute('societe_creation', new sfRoute('/societe-creation', array('module' => 'societe',
            'action' => 'creationSociete')));


        $r->prependRoute('societe_creation_doublon', new sfRoute('/societe-creation-doublon/:type/:raison_sociale', array('module' => 'societe',
            'action' => 'creationSocieteDoublon')));

        $r->prependRoute('societe_nouvelle', new sfRoute('/societe-nouvelle/:type/:raison_sociale', array('module' => 'societe',
            'action' => 'societeNew')));


        $r->prependRoute('societe_modification', new SocieteRoute('/societe/:identifiant/modification', array('module' => 'societe',
            'action' => 'modification'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));

        $r->prependRoute('societe_annulation', new SocieteRoute('/societe/:identifiant/annulation', array('module' => 'societe',
            'action' => 'annulation'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));


        $r->prependRoute('societe_visualisation', new SocieteRoute('/societe/:identifiant/visualisation', array('module' => 'societe',
            'action' => 'visualisation'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));

        $r->prependRoute('societe_addContact', new SocieteRoute('/societe/:identifiant/ajout-contact', array('module' => 'societe',
            'action' => 'addContact'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));


        $r->prependRoute('societe_addEnseigne', new SocieteRoute('/societe/:identifiant/ajout-enseigne', array('module' => 'societe',
            'action' => 'addEnseigne'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));

          $r->prependRoute('societe_switch_statut', new SocieteRoute('/societe/:identifiant/switchStatus', array('module' => 'societe',
            'action' => 'switchStatus'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')
        ));

        
        $r->prependRoute('societe_upload', new sfRoute('/societe/upload-csv-rgt-en-attente', array('module' => 'societe',
            'action' => 'upload')));


        /*         * *************
         * Intégration
         * ************* */
        $r->prependRoute('societe_creation_int', new sfRoute('/societe/creation_int', array('module' => 'societe',
            'action' => 'createSocieteInt')));
        $r->prependRoute('societe_detail_int', new sfRoute('/societe/detail_int', array('module' => 'societe',
            'action' => 'detailSocieteInt')));
    }

}
