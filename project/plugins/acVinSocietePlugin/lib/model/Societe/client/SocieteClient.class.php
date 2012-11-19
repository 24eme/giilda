<?php

class SocieteClient extends acCouchdbClient {

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT) {
        return parent::find($this->getId($id_or_identifiant), $hydrate);
    }
    
    public function getId($id_or_identifiant) {
        $id = $id_or_identifiant;
        if(strpos($id_or_identifiant, 'SOCIETE-') === false) {
            $id = 'SOCIETE-'.$id_or_identifiant;
        }

        return $id;
    }

    public static function getInstance()
    {
      return acCouchdbManager::getClient("Societe");
    }  
    
    public function getIdentifiant($id)
    {
        return $id;
    }
}
