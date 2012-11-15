<?php

class RevendicationHistoryView extends acCouchdbView
{
    const KEYS_CAMPAGNE = 0;
    const KEYS_ODG = 1;
    const KEYS_DATE = 2;
    
    const VALUE_DECLARANTS_ARRAY = 0;
    

    public static function getInstance() {
        return acCouchdbManager::getView('revendication', 'history', 'Revendication');
    }

    public function getHistory($limit = 10) {  
            return acCouchdbManager::getClient()
                    ->limit($limit)
                    ->getView($this->design, $this->view)->rows;
            
    }
    
     public function getHistoryWithCampagne($campagne, $limit = 10) {  
            return acCouchdbManager::getClient()
                    ->limit($limit)
                    ->startkey(array($campagne))
                    ->endkey(array($campagne, array()))
                    ->getView($this->design, $this->view)->rows;
            
     }
     
}  
