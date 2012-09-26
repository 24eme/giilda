<?php

class GenerationRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        
        $r = $event->getSubject();
        $r->prependRoute('generation_facture', new sfRoute('/generation/:type_document/:date_emission', array('module' => 'generation',
								  'action' => 'facture')));
        $r->prependRoute('generation_ds', new sfRoute('/generation/ds/history', array('module' => 'generation',
								  'action' => 'ds')));

    }
}