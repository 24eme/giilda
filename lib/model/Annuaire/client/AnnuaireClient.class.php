<?php

class AnnuaireClient extends acCouchdbClient 
{
	const ANNUAIRE_PREFIXE_ID = 'ANNUAIRE-';
	const ANNUAIRE_RECOLTANTS_KEY = 'recoltants';
	const ANNUAIRE_NEGOCIANTS_KEY = 'negociants';
	const ANNUAIRE_CAVES_COOPERATIVES_KEY = 'caves_cooperatives';
 	static $annuaire_types = array(
 								self::ANNUAIRE_RECOLTANTS_KEY => 'Récoltant', 
 								self::ANNUAIRE_NEGOCIANTS_KEY => 'Négociant', 
 								self::ANNUAIRE_CAVES_COOPERATIVES_KEY => 'Cave coopérative'
 	);
 	static $tiers_qualites = array(
 								self::ANNUAIRE_RECOLTANTS_KEY => _TiersClient::QUALITE_RECOLTANT,
                                self::ANNUAIRE_NEGOCIANTS_KEY => _TiersClient::QUALITE_NEGOCIANT, 
                                self::ANNUAIRE_CAVES_COOPERATIVES_KEY => _TiersClient::QUALITE_COOPERATIVE,
 	);
 	
  	public static function getAnnuaireTypes() 
  	{
  		return self::$annuaire_types;
  	}
 	
  	public static function getTiersQualites() 
  	{
  		return self::$tiers_qualites;
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
        $tiers = _TiersClient::getInstance()->findByCvi($identifiant);

        if(!$tiers) {
            $tiers = _TiersClient::getInstance()->findByCivaba($identifiant);
        }

        if($tiers->type == 'MetteurEnMarche' && $tiers->hasCvi()) {
            $tiers = $tiers->getCviObject();
        }

        $tiersQualites = self::getTiersQualites();

        if($tiers->qualite_categorie != $tiersQualites[$type]) {
            
            return null;
        }

        if(!$tiers->isActif()) {

            return null;
        }

        return $tiers;
    }
}
