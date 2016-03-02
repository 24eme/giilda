<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracStatutAndTypeView
 * @author mathurin
 */
class VracSoussigneIdentifiantView extends acCouchdbView {

    public static function getInstance() {
        return acCouchdbManager::getView('vrac', 'soussigneidentifiant', 'Vrac');
    }
    
}

