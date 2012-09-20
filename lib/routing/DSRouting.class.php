<?php

class DSRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();
        $r->prependRoute('ds', new sfRoute('/ds', array('module' => 'ds',
                    'action' => 'index')));

        $r->prependRoute('ds_etablissement', new EtablissementRoute('/ds/:identifiant', array('module' => 'ds',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')
        ));
        
        
        
        $r->prependRoute('ds_generation', new sfRoute('/ds/generation', array('module' => 'ds', 
										      'action' => 'generation')));   
        
        $r->prependRoute('ds_historique_generation', new sfRoute('/ds/generation/historique', array('module' => 'ds', 
										      'action' => 'generation'))); 
        
        $r->prependRoute('ds_generation_operateur', new EtablissementRoute('/ds/:identifiant/generation', array('module' => 'ds',
                    'action' => 'generationOperateur'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')
        ));
        
    }
}