<?php

class RevendicationClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Revendication");
    }  
}
