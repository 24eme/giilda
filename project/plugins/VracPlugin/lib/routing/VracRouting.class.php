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
        
        $r->prependRoute('vrac_nouveau_getinfos', new VracRoute('/vrac/getInfos',
                                                        array('module' => 'vrac','action' => 'getInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
       
        $r->prependRoute('vrac_soussigne_getinfos', new VracRoute('/vrac/:numero_contrat/getInfos',
                                                        array('module' => 'vrac','action' => 'getInformations'),
                                                        array('sf_method' => array('get')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        
        
        $r->prependRoute('vrac_nouveau_modification', new VracRoute('/vrac/modification',
                                                        array('module' => 'vrac','action' => 'getModifications'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
        
        $r->prependRoute('vrac_soussigne_modification', new VracRoute('/vrac/:numero_contrat/modification',
                                                        array('module' => 'vrac','action' => 'getModifications'),
                                                        array('sf_method' => array('get','post')),
                                                        array('model' => 'Vrac', 'type' => 'object')));
 
//        $r->prependRoute('vrac_nouveau_modificationPost', new VracRoute('/vrac/modificationPost',
//                                                        array('module' => 'vrac','action' => 'modificationPost'),
//                                                        array('sf_method' => array('post')),
//                                                        array('model' => 'Vrac', 'type' => 'object')));
//        
//        $r->prependRoute('vrac_soussigne_modificationPost', new VracRoute('/vrac/:numero_contrat/modificationPost',
//                                                        array('module' => 'vrac','action' => 'modificationPost'),
//                                                        array('sf_method' => array('post')),
//                                                        array('model' => 'Vrac', 'type' => 'object')));
      
    }

}