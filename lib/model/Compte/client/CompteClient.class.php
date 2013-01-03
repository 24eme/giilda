<?php

class CompteClient extends acCouchdbClient {
    
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Compte");
    }  
       
    public function createCompte($societe) {   
        $compte = new Compte();
        $compte->id_societe = $societe->_id;
        $compte->identifiant = $this->getNextIdentifiantForSociete($societe);
        $compte->interpro = 'INTERPRO-inter-loire';
        return $compte;
    }
    
    public function getId($identifiant)
    {
      return 'COMPTE-'.$identifiant;
    }

    public function getNextIdentifiantForSociete($societe)
    {   
        $id='';
	$societe_id = $societe->identifiant;
    	$comptes = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($comptes) > 0) {
            $id .= $societe_id.sprintf("%1$02d",((double)str_replace('COMPTE-', '', count($comptes)) + 1));
        } else {
            $id.= $societe_id.'01';
        }
        return $id;
    }
    
    public function getAtSociete($societe_id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('COMPTE-'.$societe_id.'00')->endkey('COMPTE-'.$societe_id.'99')->execute($hydrate);        
    }
    
    public function findByIdentifiant($identifiant) {
      return $this->find($this->getId($identifiant));
    }

    public function getAllTags() {
        return array('TAG0' => 'TAG0','TAG1' => 'TAG1');
    }

    public function createTypeFromOrigines($origines) {
      if (!count($origines))
	return "INTERLOCUTEUR";
      foreach ($origines as $o) {
	if (preg_match('/ETABLISSEMENT/', $o)) {
	  return "ETABLISSEMENT";
	}
      }
      return "SOCIETE";
    }
    
}
