<?php

class StockRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {

        $r = $event->getSubject();
        $r->prependRoute('stock', new sfRoute('/stock', array('module' => 'stock',
                    'action' => 'index')));

        $r->prependRoute('stock_etablissement', new EtablissementRoute('/stock/:identifiant', array('module' => 'stock',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')
        ));
        
        
        
        $r->prependRoute('stock_generation', new sfRoute('/stock/generation', array('module' => 'stock', 
										      'action' => 'generation')));   
        
        $r->prependRoute('stock_historique_generation', new sfRoute('/stock/generation/historique', array('module' => 'stock', 
										      'action' => 'generation'))); 
        
        $r->prependRoute('stock_generation_operateur', new EtablissementRoute('/stock/:identifiant/generation', array('module' => 'stock',
                    'action' => 'generationOperateur'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')
        ));
        
    }
}