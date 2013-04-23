<?php

class AlerteDateClient extends acCouchdbClient {
    
    const NON = 'non';
    const OUI = 'oui';
    
    public static function getInstance()
    {
      return acCouchdbManager::getClient("AlerteDate");
    }  
    
    public function buildId() {
        return sprintf('ALERTEDATE');
    }
    
    public static function getDebugsChoices() {
        return array(0 => self::NON, 1 => self::OUI);
    }

    public function getDate() {
        $alerteDate = $this->find($this->buildId());
        if(!$alerteDate) return date('Y-m-d');
        if(!$alerteDate->debug) return date('Y-m-d');
        return $alerteDate->date;
    }
    
}
