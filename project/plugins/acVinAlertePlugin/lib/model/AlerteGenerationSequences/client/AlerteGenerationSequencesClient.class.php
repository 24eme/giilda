<?php

class AlerteGenerationSequencesClient extends acCouchdbClient {
    public static function getInstance()
    {
      return acCouchdbManager::getClient("AlerteGenerationSequences");
    }  

    public function buildId($alerte_type) {
        return sprintf('ALERTEGENERATIONSEQUENCES-%s',$alerte_type);
    }


    public function findByAlerteType($alerte_type) {
        return $this->find($this->buildId($alerte_type));
    }
}
