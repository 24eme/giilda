<?php

class SV12Routing {

    /**
     * Listens to the routing.load_configuration event.
     *
     * @param sfEvent An sfEvent instance
     * @static
     */
    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        
        $r = $event->getSubject();
        $r->prependRoute('sv12', new sfRoute('/sv12', array('module' => 'sv12',
								  'action' => 'chooseEtablissement')));
        
        $r->prependRoute('sv12_etablissement', new EtablissementRoute('/sv12/:identifiant', array('module' => 'sv12', 
                                                                        'action' => 'monEspace'),
                                                    array('sf_method' => array('get','post')),
                                                    array('model' => 'Etablissement',
                                                        'type' => 'object')
                                                        ));
        
        $r->prependRoute('sv12_nouvelle', new SV12LightRoute('/sv12/:identifiant/nouvelle/:periode', 
                                                array('module' => 'sv12', 
                                                      'action' => 'nouvelle'),
                                                array('sf_method' => array('get')),
                                                array('must_be_valid' => false, 'must_be_not_valid' => false)));
        
        $r->prependRoute('sv12_update', new SV12Route('/sv12/:negociant_identifiant/edition/:periode/update',
                        array('module' => 'sv12',
                            'action' => 'update'),
                        array('sf_method' => array('get', 'post')),
                        array('model' => 'SV12',
                             'type' => 'object',
                             'must_be_valid' => false,
                             'must_be_not_valid' => true
                )));
        
        $r->prependRoute('sv12_recapitulatif', new SV12Route('/sv12/:negociant_identifiant/edition/:periode/recapitulatif',
                array('module' => 'sv12',
                    'action' => 'recapitulatif'),
                array('sf_method' => array('get', 'post')),
                array('model' => 'SV12',
                        'type' => 'object',
                        'must_be_valid' => false,
                        'must_be_not_valid' => true
        )));
        
         $r->prependRoute('sv12_visualisation', new SV12Route('/sv12/:negociant_identifiant/visualisation/:periode',
                array('module' => 'sv12',
                    'action' => 'visualisation'),
                array('sf_method' => array('get', 'post')),
                array('model' => 'SV12',
                        'type' => 'object',
                        'must_be_valid' => false,
                        'must_be_not_valid' => true
        )));
        
    }

}