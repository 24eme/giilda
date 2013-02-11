<?php

class CompteClient extends acCouchdbClient {

  const TYPE_COMPTE_SOCIETE = "SOCIETE";
  const TYPE_COMPTE_ETABLISSEMENT = "ETABLISSEMENT";
  const TYPE_COMPTE_INTERLOCUTEUR = "INTERLOCUTEUR";
  
  const STATUT_ACTIF = "ACTIF";
      
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Compte");
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

    public function findAndDelete($idCompte, $from_etablissement = false, $from_societe = false) {
        $compte = $this->find($idCompte);
        if(!$compte) return;
        $this->delete($compte);
        
        if(!$from_societe) {
            $societe = $compte->getSociete();
            $societe->removeContact($idCompte);
            $societe->save(true);
        }
        
        if(!$from_etablissement) {
            throw new sfException("Not yet implemented");
        }
    }


    public function getAllTags() {
        return array('TAG0' => 'TAG0','TAG1' => 'TAG1');
    }

    public function createTypeFromOrigines($origines) {
      if (!count($origines))
	return self::TYPE_COMPTE_INTERLOCUTEUR;
      foreach ($origines as $o) {
	if (preg_match('/ETABLISSEMENT/', $o)) {
	  return self::TYPE_COMPTE_ETABLISSEMENT;
	}
      }
      return self::TYPE_COMPTE_SOCIETE;
    }
    
    
    public function findOrCreateCompteSociete($societe) {
        $compte = null;
        if($societe->compte_societe) {
            $compte = $this->find($societe->compte_societe);
        }
        
        if(!$compte) {
             $compte = $this->createCompteFromSociete($societe);
        }
        
        return $compte;
    }
   
        
    public function findOrCreateCompteFromEtablissement($e) {
        $compte = $this->find($e->getNumCompteEtablissement());
        
        if(!$compte) {
         
            $compte = $this->createCompteFromEtablissement($e);
        }
        
        return $compte;
    }
    
    public function createCompteFromSociete($societe) {
        $compte = new Compte();        
        $compte->id_societe = $societe->_id;
        if ($societe->siege->adresse) {
            $compte->adresse = $societe->siege->adresse;
	    $compte->code_postal = $societe->siege->code_postal;
	    $compte->commune = $societe->siege->commune;
            $societe->siege->add('pays');
            $compte->pays = $societe->siege->pays;
          }
        $compte->identifiant = $this->getNextIdentifiantForSociete($societe);
        $compte->constructId();
        $compte->interpro = 'INTERPRO-inter-loire';
        
        return $compte;
    }
    
    public function createCompteFromEtablissement($e) {
      $compte = $this->createCompteFromSociete($e->getSociete());
      
      $compte->nom = $e->nom;
      $compte->email = $e->email;
      $compte->fax = $e->fax;
      $compte->telephone_bureau = $e->telephone;
      $compte->statut = $e->statut;
      if ($e->siege->adresse) {
    	$compte->adresse = $e->siege->adresse;
	    $compte->code_postal = $e->siege->code_postal;
	    $compte->commune = $e->siege->commune;
      }
      
      $compte->addOrigine($e->_id);

      return $compte;
    }
    
}
