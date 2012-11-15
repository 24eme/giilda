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

    public function createOrFindDoc($odg,$campagne,$path) {
        $revendication = $this->find($this->getId($odg, $campagne));
        
        if (!$revendication) {
            $revendication = new Revendication();
            $revendication->campagne = $campagne;
            $revendication->odg = $odg;
            $revendication->_id = $this->getId($odg, $campagne);
            $revendication->date_creation = date('Y-m-d');
            $revendication->etape = 2;
            $revendication->save();
        }
        
        $revendication->storeAttachment($path, 'text/csv', 'revendication.csv');
        $revendication = $this->find($revendication->get('_id'));
        $revendication->storeDatas();
        
        return $revendication;
    }    
    
    public function getHistory() {
        return RevendicationHistoryView::getInstance()->getHistory();
    }

}
