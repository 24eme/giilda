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
        $r->prependRoute('generation_view', new sfRoute('/generation/:type_document/:date_emission', array('module' => 'generation',
								  'action' => 'view')));
        $r->prependRoute('generation_delete', new sfRoute('/generation/:type_document/:date_emission/delete', array('module' => 'generation',
								  'action' => 'delete')));
        $r->prependRoute('generation_list', new sfRoute('/generation/list/:type_document', array('module' => 'generation',
								  'action' => 'list')));
        $r->prependRoute('generation_reload', new sfRoute('/generation/:type_document/:date_emission/reload', array('module' => 'generation',
								  'action' => 'reload')));

    }
}
