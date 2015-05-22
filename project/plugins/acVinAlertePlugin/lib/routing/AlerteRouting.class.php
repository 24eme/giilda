<?php

class AlerteRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        
        $r = $event->getSubject();
        
        $r->prependRoute('alerte', new sfRoute('/alerte', array('module' => 'alerte',
                    'action' => 'index')));        
        
        $r->prependRoute('alerte_etablissement', new EtablissementRoute('/alerte/:identifiant/recherche', array('module' => 'alerte',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
        
        $r->prependRoute('alerte_modification', new AlerteRoute('/alerte/:type_alerte/:id_document/modification',
                                                            array('module' => 'alerte',
                                                                  'action' => 'modification'),
                                                            array('sf_method' => array('get', 'post')),
                                                            array('model' => 'Alerte',
                                                                  'type' => 'object')));
        
        $r->prependRoute('alerte_modification_statuts', new sfRoute('/alerte/statutsModification/:retour', array('module' => 'alerte',
                    'action' => 'statutsModification', 'retour' => null))); 
        
        $r->prependRoute('alerte_generate_all', new sfRoute('/alerte/generationTotale', array('module' => 'alerte',
                    'action' => 'generationTotale')));
    }
}
