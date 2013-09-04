<?php

class AnnuaireClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Annuaire");
    }  
}
