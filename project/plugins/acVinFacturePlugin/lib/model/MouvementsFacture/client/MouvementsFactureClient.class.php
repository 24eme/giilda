<?php

class MouvementsFactureClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("MouvementsFacture");
    }  
}
