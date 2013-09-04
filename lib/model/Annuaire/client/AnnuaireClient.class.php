<?php

class AnnuaireClient extends acCouchdbClient 
{
	const ANNUAIRE_PREFIXE_ID = 'ANNUAIRE-';
	const ANNUAIRE_ACHETEURS_KEY = 'acheteurs';
	const ANNUAIRE_VENDEURS_KEY = 'vendeurs';
 	static $annuaire_types = array(
 								self::ANNUAIRE_ACHETEURS_KEY => 'Acheteur', 
 								self::ANNUAIRE_VENDEURS_KEY => 'Vendeur'
 	);
 	static $tiers_correspondance_types = array(
 								'Acheteur' => self::ANNUAIRE_ACHETEURS_KEY, 
 								'Recoltant' => self::ANNUAIRE_VENDEURS_KEY
 	);
 	
  	public static function getAnnuaireTypes() 
  	{
  		return self::$annuaire_types;
  	}
 	
  	public static function getTiersCorrespondanceTypes() 
  	{
  		return self::$tiers_correspondance_types;
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
}
