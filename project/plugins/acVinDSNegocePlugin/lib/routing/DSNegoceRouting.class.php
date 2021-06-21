<?php

class DSNegoceRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();
        $r->prependRoute('dsnegoce', new sfRoute('/dsnegoce', array('module' => 'dsnegoce',
                    'action' => 'index')));

        $r->prependRoute('dsnegoce_etablissement', new EtablissementRoute('/dsnegoce/:identifiant', array('module' => 'dsnegoce',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
    }
}
