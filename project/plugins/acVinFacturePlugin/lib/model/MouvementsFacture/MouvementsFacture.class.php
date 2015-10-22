<?php
/**
 * Model for MouvementsFacture
 *
 */

class MouvementsFacture extends BaseMouvementsFacture {

    
     public function constructIds($date) {
         if(!$date){
             $date = date('Ymd');
         }
         
        $this->identifiant = MouvementsFactureClient::getInstance()->getNextNoFacture($date);
        $this->_id = MouvementsFactureClient::getInstance()->getId($this->identifiant);
    }
}