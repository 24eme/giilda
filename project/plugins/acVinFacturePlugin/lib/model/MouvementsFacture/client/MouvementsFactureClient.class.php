<?php

class MouvementsFactureClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("MouvementsFacture");
    }

    public function createMouvementsFacture($date = null) {
        $mouvementsFacture = new MouvementsFacture();
        $mouvementsFacture->constructIds($date);  
        return $mouvementsFacture;
    }

    public function getId($identifiant) {
        return 'MOUVEMENTSFACTURE-'.$identifiant;
    }
    public function getNextNoFacture($date)
    {   
        $id = '';
    	$mouvementsfacture = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($mouvementsfacture) > 0) {
            $id .= ((double)str_replace('FACTURE-'.$date.'-', '', max($mouvementsfacture)) + 1);
        } else {
            $id.= $date.'01';
        }
        return $id;
    }
    
    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('MOUVEMENTSFACTURE-'.$date.'00')->endkey('MOUVEMENTSFACTURE-'.$date.'99')->execute($hydrate);        
    }
}
