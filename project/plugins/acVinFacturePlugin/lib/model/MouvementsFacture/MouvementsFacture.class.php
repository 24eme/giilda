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
         
        $this->identifiant = MouvementsFactureClient::getInstance()->getNextNoMouvementsFacture($date);
       
        $this->_id = MouvementsFactureClient::getInstance()->getId($this->identifiant);
    }
}