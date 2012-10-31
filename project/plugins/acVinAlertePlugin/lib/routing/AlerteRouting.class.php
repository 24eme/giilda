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
        
        $r->prependRoute('alerte_etablissement', new EtablissementRoute('/alerte/:identifiant', array('module' => 'alerte',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
        
    }
}
