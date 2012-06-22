<?php

class EtablissementClient extends acCouchdbClient {
   
    const FAMILLE_NEGOCE = 'Negociant';
    const FAMILLE_VITICULTEUR = 'Viticulteur';
    const FAMILLE_COURTIER = 'Courtier';
    
    /**
     *
     * @return EtablissementClient
     */
    public static function getInstance() {
        
        return acCouchdbManager::getClient("Etablissement");
    }
    
    /**
     *
     * @param string $identifiant
     * @param integer $hydrate
     * @return Etablissement 
     */
    public function findByIdentifiant($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::find('ETABLISSEMENT-'.$identifiant, $hydrate);
    }

    public function findByFamille($famille) {
        
        return $this->startkey(array($famille))
              ->endkey(array($famille, array()))->limit(100)->getView('etablissement', 'tous');
    }
    
    public function findAll() 
    {
        
        return $this->limit(100)->getView('etablissement', 'tous');
    }
    
}
