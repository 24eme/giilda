<?php

class ComptabiliteClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Comptabilite");
    }  
    
    public function findCompta() {
        return $this->find('COMPTABILITE');
    }
}
