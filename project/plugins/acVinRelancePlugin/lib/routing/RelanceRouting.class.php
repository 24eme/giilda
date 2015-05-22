<?php

class RelanceRouting {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        
        $r = $event->getSubject();
        
        $r->prependRoute('relance', new sfRoute('/relance', array('module' => 'relance',
                         'action' => 'index')));        
        
        $r->prependRoute('relance_etablissement', new EtablissementRoute('/relance/:identifiant', array('module' => 'relance',
                    'action' => 'monEspace'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
        
        $r->prependRoute('relance_etablissement_creation', new EtablissementRoute('/relance/:identifiant/creation', array('module' => 'relance',
                    'action' => 'genererEtablissement'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
        
                $r->prependRoute('relance_etablissement_creation_ar', new EtablissementRoute('/relanceAr/:identifiant/creation', array('module' => 'relance',
                    'action' => 'genererArEtablissement'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'Etablissement',
                            'type' => 'object')));
        
        
        
        $r->prependRoute('relance_pdf', new RelanceRoute('/relance/:idrelance/pdf', array('module' => 'relance', 
													'action' => 'latex'),
									 array('sf_method' => array('get','post')),
									 array('model' => 'Relance',
									       'type' => 'object')
									 ));
                $r->prependRoute('relance_generation', new sfRoute('/relance/generation', array('module' => 'relance', 
										      'action' => 'generation')));
        
    }
}
