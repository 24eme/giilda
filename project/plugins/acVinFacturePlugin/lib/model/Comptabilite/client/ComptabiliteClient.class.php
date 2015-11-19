<?php

class ComptabiliteClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Comptabilite");
    }  
}
