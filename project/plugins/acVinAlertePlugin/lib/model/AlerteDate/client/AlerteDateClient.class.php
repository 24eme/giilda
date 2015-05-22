<?php

class AlerteDateClient extends acCouchdbClient {
    
    
    public static function getInstance()
    {
      return acCouchdbManager::getClient("AlerteDate");
    }  
    
    public function buildId() {
        return sprintf('ALERTEDATE');
    }
    

    public function getDate() {
        $alerteDate = $this->find($this->buildId());
        if(!$alerteDate) return date('Y-m-d');
        $debugMode = sfConfig::get('app_alertes_debug',false);
        if(!$debugMode) return date('Y-m-d');
        return $alerteDate->date;
    }
    
}
