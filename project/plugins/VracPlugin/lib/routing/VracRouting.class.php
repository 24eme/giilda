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
        $r->prependRoute('vrac_soussigne', new sfRoute('/vrac/nouveau-soussigne', array('module' => 'vrac',
                                                            'action' => 'nouveau')));
        $r->prependRoute('vrac_marche', new VracRoute('/vrac/:numero_contrat/marche',
                                                        array('module' => 'vrac','action' => 'marche'), array('sf_method' => array('get','post')),
                                                                                      array('model' => 'Vrac', 'type' => 'object')));       
        $r->prependRoute('vrac_condition', new VracRoute('/vrac/:numero_contrat/condition',
                                                        array('module' => 'vrac','action' => 'condition'), array('sf_method' => array('get','post')),
                                                                                      array('model' => 'Vrac', 'type' => 'object')));
        $r->prependRoute('vrac_validation', new VracRoute('/vrac/:numero_contrat/validation',
                                                        array('module' => 'vrac','action' => 'validation'), array('sf_method' => array('get','post')),
                                                                                      array('model' => 'Vrac', 'type' => 'object')));
    }

}