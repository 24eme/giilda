<?php

class AnnuaireClient extends acCouchdbClient 
{
	const ANNUAIRE_PREFIXE_ID = 'ANNUAIRE-';
	const ANNUAIRE_RECOLTANT_KEY = 'recoltants';
	const ANNUAIRE_NEGOCIANTS_KEY = 'negociants';
	const ANNUAIRE_CAVES_COOPERATIVES_KEY = 'caves_cooperatives';
 	static $annuaire_types = array(
 								self::ANNUAIRE_RECOLTANT_KEY => 'Récoltant', 
 								self::ANNUAIRE_NEGOCIANTS_KEY => 'Négociants', 
 								self::ANNUAIRE_CAVES_COOPERATIVES_KEY => 'Cave coopérative'
 	);
 	
  	public static function getAnnuaireTypes() 
  	{
  		return self::$annuaire_types;
  	}
	
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Annuaire");
    }  
    
    public static function getTiersCorrespondanceType($tiersType)
    {
    	$types = self::getTiersCorrespondanceTypes();
    	return $types[$tiersType];
    }
    
    public function createAnnuaire($cvi)
    {
    	$annuaire = new Annuaire();
    	$annuaire->cvi = $cvi;
    	$annuaire->save();
    	return $annuaire;
    }
    
    public function findOrCreateAnnuaire($cvi)
    {
    	if ($annuaire = $this->find(self::ANNUAIRE_PREFIXE_ID.$cvi)) {
    		return $annuaire;
    	}
    	return $this->createAnnuaire($cvi);
    }

    public function buildId($cvi)
    {
      return self::ANNUAIRE_PREFIXE_ID.$cvi;
    }

    public function findTiersByTypeAndIdentifiant($type, $identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
    	if ($type == self::ANNUAIRE_RECOLTANT_KEY) {
        	$tiers = parent::find('REC-'.$identifiant, $hydrate);
    	} else {
            $tiers = parent::find('ACHAT-'.$identifiant, $hydrate);
    	}
        if(!$tiers) {
			$tiers = parent::find('MET-'.$identifiant, $hydrate);
        }
        return $tiers;
    }
}
