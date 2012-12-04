<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acVinComptePlugin configuration.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class acVinCompteRouting
{
  /**
   * Listens to the routing.load_configuration event.
   *
   * @param sfEvent An sfEvent instance
   * @static
   */
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();
    $r->prependRoute('ac_vin_login', new sfRoute('/', array('module' => 'acVinCompte', 'action' => 'login')));
    $r->prependRoute('ac_vin_logout', new sfRoute('/logout', array('module' => 'acVinCompte', 'action' => 'logout')));
    $r->prependRoute('ac_vin_compte', new sfRoute('/compte', array('module' => 'acVinCompte', 'action' => 'first')));
    $r->prependRoute('ac_vin_compte_creation', new sfRoute('/compte/creation', array('module' => 'acVinCompte', 'action' => 'creation')));
    $r->prependRoute('ac_vin_compte_modification_oublie', new sfRoute('/compte/mot_de_passe_oublie', array('module' => 'acVinCompte', 'action' => 'modificationOublie')));
    $r->prependRoute('ac_vin_compte_modification', new sfRoute('/mon_compte', array('module' => 'acVinCompte', 'action' => 'modification')));
    $r->prependRoute('ac_vin_compte_mot_de_passe_oublie_login', new sfRoute('/mot_de_passe_oublie/login/:login/:mdp', array('module' => 'acVinCompte', 'action' => 'motDePasseOublieLogin')));
    $r->prependRoute('ac_vin_compte_mot_de_passe_oublie', new sfRoute('/mot_de_passe_oublie', array('module' => 'acVinCompte', 'action' => 'motDePasseOublie')));
    $r->prependRoute('ac_vin_compte_mot_de_passe_oublie_confirm', new sfRoute('/mot_de_passe_oublie/confirm', array('module' => 'acVinCompte', 'action' => 'motDePasseOublieConfirm')));
  
     $r->prependRoute('compte_modification', new CompteRoute('/compte/:identifiant/modification',
                        array('module' => 'compte',
                            'action' => 'modification'),
                        array('sf_method' => array('get', 'post')),
                    array('model' => 'Compte',
                        'type' => 'object') ));
    
  }
}