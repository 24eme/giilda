<?php

class VracClient extends acCouchdbClient {
   
    /**
     *
     * @return DRMClient
     */
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Vrac");
    }

    public function getId($numeroContrat)
    {
      return 'VRAC-'.$numeroContrat;
    }

    public function getNextNoContrat()
    {   
        $id = '';
    	$date = date('Ymd');
    	$contrats = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($contrats) > 0) {
            $id .= ((double)str_replace('VRAC-', '', max($contrats)) + 1);
        } else {
            $id.= $date.'001';
        }

        return $id;
    }
    
    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('VRAC-'.$date.'000')->endkey('VRAC-'.$date.'999')->execute($hydrate);
        
    }
    
    public function findByNumContrat($num_contrat) {
      return $this->find($this->getId($num_contrat));
    }

}
