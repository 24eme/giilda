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
        $r->prependRoute('vrac_nouveau', new sfRoute('/vrac/nouveau', array('module' => 'vrac',
                                                            'action' => 'nouveau')));
        $r->prependRoute('vrac_soussigne', new VracRoute('/vrac/:numero_contrat/soussigne',
                                                        array('module' => 'vrac','action' => 'soussigne'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));       
        $r->prependRoute('vrac_marche', new VracRoute('/vrac/:numero_contrat/marche',
                                                        array('module' => 'vrac','action' => 'marche'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));       
        $r->prependRoute('vrac_condition', new VracRoute('/vrac/:numero_contrat/condition',
                                                        array('module' => 'vrac','action' => 'condition'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_validation', new VracRoute('/vrac/:numero_contrat/validation',
                                                        array('module' => 'vrac','action' => 'validation'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_termine', new VracRoute('/vrac/:numero_contrat/recapitulatif',
                                                        array('module' => 'vrac','action' => 'recapitulatif'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object'))); 
        
        $r->prependRoute('vrac_getvendeurinfos', new VracRoute('/vrac/vendeurInformations',
                                                        array('module' => 'vrac','action' => 'getVendeurInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_getacheteurinfos', new VracRoute('/vrac/acheteurInformations',
                                                        array('module' => 'vrac','action' => 'getAcheteurInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_getmandataireinfos', new VracRoute('/vrac/mandataireInformations',
                                                        array('module' => 'vrac','action' => 'getMandataireInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_vendeurModification', new VracRoute('/vrac/vendeurModification',
                                                        array('module' => 'vrac','action' => 'getVendeurModification'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        
        $r->prependRoute('vrac_acheteurModification', new VracRoute('/vrac/acheteurModification',
                                                        array('module' => 'vrac','action' => 'getAcheteurModification'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        
        $r->prependRoute('vrac_mandataireModification', new VracRoute('/vrac/mandataireModification',
                                                        array('module' => 'vrac','action' => 'getMandataireModification'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
    }

}