<?php

class SocieteClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Societe");
    }  
    
    public function getIdentifiant($id)
    {
        return $id;
    }
}
