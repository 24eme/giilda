<?php

class ConfigurationRouting {

    static public function listenToRoutingLoadConfigurationEvent(sfEvent $event) {
        $r = $event->getSubject();
        
        $r->prependRoute('produits', new sfRoute('/produits', array('module' => 'produit', 
                                                                  'action' => 'index')));

        $r->prependRoute('produit_modification', new sfRoute('/produits/modification/:noeud/:hash', 
            array('module' => 'produit', 'action' => 'modification', 'hash' => null, 'noeud' => null)));

        $r->prependRoute('produit_nouveau', new sfRoute('/produits/nouveau', 
            array('module' => 'produit', 'action' => 'nouveau', 'hash' => null, 'noeud' => null)));

    }
 }
