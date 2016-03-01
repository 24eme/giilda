<?php

class CompteClient extends acCouchdbClient {
  
    const TYPE_COMPTE_SOCIETE = "SOCIETE";
    const TYPE_COMPTE_ETABLISSEMENT = "ETABLISSEMENT";
    const TYPE_COMPTE_INTERLOCUTEUR = "INTERLOCUTEUR";
    
    const STATUT_ACTIF = "ACTIF";

    const STATUT_TELEDECLARANT_NOUVEAU = "NOUVEAU";
    const STATUT_TELEDECLARANT_INSCRIT = "INSCRIT";
    const STATUT_TELEDECLARANT_OUBLIE = "OUBLIE";
    const STATUT_TELEDECLARANT_INACTIF = "INACTIF";
      
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Compte");
    }

    public function getId($identifiant)
    {
        return 'COMPTE-'.sprintf('%08d', $identifiant);
    }

    public function getNextIdentifiantForSociete($societe)
    {   
  	  $societe_id = $societe->identifiant;
    	$comptes = self::getAtSociete($societe_id, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
      $last_num = 0;
      foreach($comptes as $id) {
        if(!preg_match('/COMPTE-[0-9]{6}([0-9]{2})/', $id, $matches)) {
          continue;
        }

        $num = $matches[1];
        if($num > $last_num) {
          $last_num = $num;
        }
      }

      return  sprintf("%s%02d", $societe_id, $last_num + 1);
    }
    
    public function getAtSociete($societe_id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('COMPTE-'.$societe_id.'00')->endkey('COMPTE-'.$societe_id.'99')->execute($hydrate);        
    }
    
    public function findByIdentifiant($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      return $this->find($this->getId($identifiant), $hydrate);
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
      foreach ($origines as $o) {
        if (preg_match('/SOCIETE/', $o)) {
          return self::TYPE_COMPTE_SOCIETE;
        }
      }

      foreach ($origines as $o) {
        if (preg_match('/ETABLISSEMENT/', $o)) {
          return self::TYPE_COMPTE_ETABLISSEMENT;
        }
      }

      return self::TYPE_COMPTE_INTERLOCUTEUR;
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
            $compte->adresse_complementaire = ($societe->siege->exist('adresse_complementaire'))? $societe->siege->adresse_complementaire : "";
      	    $compte->code_postal = $societe->siege->code_postal;
      	    $compte->commune = $societe->siege->commune;
            //$societe->siege->add('pays');
            $compte->pays = $societe->getMasterCompte()->pays;
          }
        $compte->identifiant = $this->getNextIdentifiantForSociete($societe);
        $compte->constructId();
        $compte->interpro = 'INTERPRO-declaration';
        $compte->synchroFromSociete();
        
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
            $compte->adresse_complementaire = ($e->siege->exist('adresse_complementaire'))? $e->siege->adresse_complementaire : "";
	    $compte->code_postal = $e->siege->code_postal;
	    $compte->commune = $e->siege->commune;
      }
      
      $compte->addOrigine($e->_id);
      $compte->synchroFromSociete();

      return $compte;
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Compte 
     */
    public function retrieveByLogin($login, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->findByLogin($login, $hydrate);
    }

    public function findByLogin($login, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $societe = SocieteClient::getInstance()->findByIdentifiantSociete($login);

        if(!$societe) {

          return null;
        }

        return $societe->getMasterCompte();
    }
    
}
