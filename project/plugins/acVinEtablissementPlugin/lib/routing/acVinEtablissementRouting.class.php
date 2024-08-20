<?php

class acVinEtablissementRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('etablissement_autocomplete_all', new sfRoute('/etablissement/autocomplete/:interpro_id/tous', array('module' => 'etablissement_autocomplete',
            'action' => 'all')));


        $r->prependRoute('etablissement_autocomplete_byfamilles', new sfRoute('/etablissement/autocomplete/:interpro_id/familles/:familles', array('module' => 'etablissement_autocomplete',
            'action' => 'byFamilles')));

        $r->prependRoute('etablissement_ajout', new SocieteRoute('/etablissement/:identifiant/nouveau', array('module' => 'etablissement',
            'action' => 'ajout'), array('sf_method' => array('get', 'post')), array('model' => 'Societe',
            'type' => 'object')));

        $r->prependRoute('etablissement_modification', new EtablissementCompteRoute('/etablissement/:identifiant/modification', array('module' => 'etablissement',
            'action' => 'modification'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')));

        $r->prependRoute('etablissement_visualisation', new EtablissementCompteRoute('/etablissement/:identifiant/visualisation', array('module' => 'etablissement',
            'action' => 'visualisation'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')));

        $r->prependRoute('etablissement_update_coordonnees_latlon', new EtablissementCompteRoute('/etablissement/:identifiant/updateLatLon', array('module' => 'etablissement',
            'action' => 'updateCoordonneesLatLon'), array('sf_method' => array('get')), array('model' => 'Etablissement',
            'type' => 'object')));

        $r->prependRoute('etablissement_switch_statut', new EtablissementCompteRoute('/etablissement/:identifiant/switchStatus', array('module' => 'etablissement',
            'action' => 'switchStatus'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('etablissement_edition_chai', new EtablissementCompteRoute('/etablissement/:identifiant/chai-modification/:num', array('module' => 'etablissement',
            'action' => 'chaiModification'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('etablissement_ajout_chai', new EtablissementCompteRoute('/etablissement/:identifiant/chai-ajout', array('module' => 'etablissement',
            'action' => 'chaiAjout'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('etablissement_suppression_chai', new EtablissementCompteRoute('/etablissement/:identifiant/chai-suppression/:num', array('module' => 'etablissement',
            'action' => 'chaiSuppression'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('etablissement_ajout_relation', new EtablissementCompteRoute('/etablissement/:identifiant/relation-ajout', array('module' => 'etablissement',
            'action' => 'relationAjout'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('etablissement_ajout_relation_chai', new EtablissementCompteRoute('/etablissement/:identifiant/relation-ajout-chai/', array('module' => 'etablissement',
            'action' => 'relationAjoutChai'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

        $r->prependRoute('etablissement_suppression_relation', new EtablissementCompteRoute('/etablissement/:identifiant/relation-suppression/:key', array('module' => 'etablissement',
            'action' => 'relationSuppression'), array('sf_method' => array('get', 'post')), array('model' => 'Etablissement',
            'type' => 'object')
        ));

    }

}
