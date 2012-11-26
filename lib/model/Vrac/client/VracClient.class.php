<?php

class VracClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Vrac");
    }  
}
