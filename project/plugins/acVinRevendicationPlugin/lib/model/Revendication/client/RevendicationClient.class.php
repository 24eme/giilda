<?php

class RevendicationClient extends acCouchdbClient {
    
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Revendication");
    }  
    
    public function getId($odg,$campagne) {
        return 'REVENDICATION-' . strtoupper($odg) . '-' . $campagne;
    }
    
    public function findByOdgAndCampagne($odg,$campagne) {
        return $this->find($this->getId($odg, $campagne));
    }

    public function getVolumeProduitObj($revendication,$cvi,$row) {
        $result = new stdClass();
        $result->produit = $revendication->getProduitNode($cvi,$row);
        $result->volume = $produit->volumes->get($row);
        return $result;
    }

    public function createDoc($odg,$campagne,$path) {        
        $revendication = new Revendication();
        $revendication->campagne = $campagne;
        $revendication->odg = $odg;
        $revendication->_id = $this->getId($odg, $campagne);
        $revendication->save();
        $revendication->storeAttachment($path, 'text/csv', 'revendication.csv');
        $revendication = $this->find($revendication->get('_id'));
        $revendication->storeDatas();
        return $revendication;
    }    

}
