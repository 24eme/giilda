<?php

class CSVDAEClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("CSVDAE");
    }  
}
