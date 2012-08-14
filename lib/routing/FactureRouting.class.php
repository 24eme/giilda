<?php

class FactureRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        
        $r = $event->getSubject();
        $r->prependRoute('facture', new sfRoute('/facture', array('module' => 'facture',
								  'action' => 'index')));
        $r->prependRoute('facture_etablissement', new EtablissementRoute('/facture/:identifiant', array('module' => 'facture', 
													'action' => 'monEspace'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Etablissement',
									       'type' => 'object')
									 ));
        
        $r->prependRoute('facture_pdf', new EtablissementRoute('/facture/:identifiant/view/:factureid/pdf', array('module' => 'facture', 
													'action' => 'latex'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Etablissement',
									       'type' => 'object')
									 ));
        
    }

}