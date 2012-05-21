<?php

class VracRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        $r->prependRoute('vrac', new sfRoute('/vrac', array('module' => 'vrac',
                                                            'action' => 'index')));

    }

}