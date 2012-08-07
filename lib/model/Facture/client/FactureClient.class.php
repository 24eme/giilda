<?php

class FactureClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Facture");
    }  
}
