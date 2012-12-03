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

        $r->prependRoute('societe_autocomplete_all', new sfRoute('/societe/autocomplete/:interpro_id/tous',
                        array('module' => 'societe',
                            'action' => 'all')));

        $r->prependRoute('societe', new sfRoute('/societe', array('module' => 'societe',
                    'action' => 'index')));

        $r->prependRoute('societe_creation', new sfRoute('/societe/creation', array('module' => 'societe',
                    'action' => 'createSociete')));
        
 	    $r->prependRoute('societe_choose', new SocieteRoute('/societe/:identifiant/espace', array('module' => 'societe', 
                                                                        'action' => 'monEspace'),
                                                    array('sf_method' => array('get','post')),
                                                    array('model' => 'Societe',
                                                        'type' => 'object')
                                                        ));
		/***************
		 * Intégration
		 ***************/							
		$r->prependRoute('societe_creation_int', new sfRoute('/societe/creation_int', array('module' => 'societe',
                    'action' => 'createSocieteInt')));
		$r->prependRoute('societe_detail_int', new sfRoute('/societe/detail_int', array('module' => 'societe',
                    'action' => 'detailSocieteInt')));
    }

}