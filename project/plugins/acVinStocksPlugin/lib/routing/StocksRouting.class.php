<?php

class StocksRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();

        $r->prependRoute('stocks', new sfRoute('/stocks', array('module' => 'stocks',
                    'action' => 'index')));
        
        $r->prependRoute('stocks_etablissement', new EtablissementRoute('/stocks/:identifiant/:campagne', array('module' => 'stocks',
                        'action' => 'monEspace', 'campagne' => null),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
    }
}