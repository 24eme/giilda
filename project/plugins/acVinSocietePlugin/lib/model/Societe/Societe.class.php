<?php

/**
 * Model for Societe
 *
 */
class Societe extends BaseSociete {

  private $changedCooperative = null;
  private $changedStatut = null;

    public function constructId() {
        $this->set('_id', 'SOCIETE-' . $this->identifiant);
    }
    
    public function removeContact($idContact){
        if($this->contacts->exist($idContact)){
            $this->contacts->remove($idContact);
        }
    }

    public function isInCreation() {

        return $this->statut == SocieteClient::STATUT_EN_CREATION;
    }


    public function addNewEnseigne() {
        $this->enseignes->add(count($this->enseignes),"");
    }

    
    public function getInterlocuteursWithOrdre() {        
        foreach ($this->contacts as $key => $interlocuteur) {
            if(is_null($interlocuteur->ordre)) $interlocuteur->ordre = 2;
        }
       // $interlocuteursTries = usort($this->contacts->toArray(), array("Societe" ,"cmpOrdreContacts"));
        return $this->contacts;
    }

    public function getMaxOrdreContacts() {
        $max = 0;
        foreach ($this->contacts as $contact) {
            if ($max < $contact->ordre)
                $max = $contact->ordre;
        }
        return $max;
    }

    public function hasChais() {
	return count($this->etablissements);
    }

    public function canHaveChais() {
        return in_array($this->type_societe, SocieteClient::getSocieteTypesWithChais());
    }

    public function getFamille() {
      if (!$this->canHaveChais())
	throw new sfException('La societe '.$this->identifiant." ne peut pas avoir famille n'ayant pas d'établissement");
      return $this->getTypeSociete();
    }

    public function getRegionViticole() {
      $regions = $this->getRegionsViticoles();
      if (count($regions) > 1)
	throw new sfException("La societe ".$this->identifiant." est reliée des établissements de plusieurs régions viticoles, ce qui n'est pas permis");
      if (!count($regions))
	throw new sfException("La societe ".$this->identifiant." n'a pas de région viti :(");
      return array_shift($regions);
    }

    private function getRegionsViticoles() {
      $regions = array();
      foreach($this->getEtablissementsObj() as $id => $e) {
	if ($e->etablissement->isActif()) {
	  $regions[$e->etablissement->region] = $e->etablissement->region;
	}
      }
      return $regions;
    }

    public function getEtablissementsObj() {
        $etablissements = array();
        foreach ($this->etablissements as $id => $obj) {
            $etablissements[$id] = new stdClass();
            $etablissements[$id]->etablissement =  EtablissementClient::getInstance()->find($id);
            $etablissements[$id]->ordre = $obj->ordre;
        }
        return $etablissements;
    }
    
    public function getComptesObj() {
        $comptes = array();
        foreach ($this->contacts as $id => $obj) {
            $comptes[$id] = new stdClass();
            $comptes[$id]->compte =  CompteClient::getInstance()->find($id);
        }
        return $comptes;
    }

    public function addEtablissement($e, $ordre = null) {
        if (! $this->etablissements->exist($e->_id)) {
                $this->etablissements->add($e->_id, array('nom' => $e->nom, 'ordre' => $ordre));
	} else {
		$this->etablissements->add($e->_id)->nom = $e->nom;
		if ($ordre !== null) {
		  $ordre = 0;
		}
		$this->etablissements->add($e->_id)->ordre = $ordre;
	}
	if ($e->compte) {
	  $this->addCompte($e->getMasterCompte(), $ordre);
	}
    }

    public function setCooperative($c) {
      $this->_set('cooperative', $c);
      foreach($this->getEtablissementsObj() as $e) {
	$e->cooperative = $c;
      }
      $this->changedCooperative = true;
    }
    
    public function setStatut($s) {
      $this->_set('statut', $s);
      foreach($this->getEtablissementsObj() as $e) {
	$e->statut = $s;
      }
      $this->changedStatut = true;
    }

    public function addCompte($c, $ordre = null) {
      if (!$this->compte_societe) {
	$this->compte_societe = $c->_id;
      }
      if (!$c->_id) {
	return ;
      }
      if (!$ordre) {
	$ordre = 0;
      }

      $cid = 'COMPTE-'.$c->identifiant;
      if (!$this->contacts->exist($c->_id)) {
	$this->contacts->add($cid, array('nom' => $c->nom_a_afficher, 'ordre' => $ordre));
      }else{
	$this->contacts->add($cid)->nom = $c->nom_a_afficher;
	$this->contacts->add($cid)->ordre = $ordre;
      }
    }
    
    public static function cmpOrdreContacts($a, $b)
    {
        if ($a->ordre == $b->ordre) {
            return 0;
        }
        return (intval($a->ordre) < intval($b->ordre)) ? -1 : 1;
    }

    public function getMasterCompte() {
        
        return CompteClient::getInstance()->find($this->compte_societe);
    }
    
    public function getContact() {
        
        return $this->getMasterCompte();
    }
    
  
    public function isManyEtbPrincipalActif(){
        $cptActif = 0;
        foreach ($this->getEtablissementsObj() as $etb) {
            if($etb->etablissement->isSameContactThanSociete() && $etb->etablissement->isActif()){
                $cptActif++;
            } 
            if($cptActif > 1) return true;
        }
        return false;
    }
    
    public function isNegoOrViti() {
        return ($this->type_societe == SocieteClient::SUB_TYPE_VITICULTEUR)
        || ($this->type_societe == SocieteClient::SUB_TYPE_NEGOCIANT);
    }

    public function isCourtier() {
        return $this->type_societe == SocieteClient::SUB_TYPE_COURTIER;
    }

    public function hasNumeroCompte() {
        return ($this->code_comptable_client || $this->code_comptable_fournisseur);
    }
    
    public function getSiegeAdresses() {
      $a = $this->siege->adresse;
      if ($this->siege->exist("adresse_complementaire")) {
	$a .= ' ; '.$this->siege->adresse_complementaire;
      }
      return $a;
    }

    public function synchroFromCompte() {
        $compte = $this->getMasterCompte();
        
        if(!$compte) {
            
            throw new sfException("Pas de compte societe. Bizarre !");
        }
        
        $this->siege->adresse = $compte->adresse;
        if($compte->exist("adresse_complementaire"))
            $this->siege->add("adresse_complementaire",$compte->adresse_complementaire);
        $this->siege->code_postal = $compte->code_postal;
        $this->siege->commune = $compte->commune;
        $this->email = $compte->email;
        $this->fax = $compte->fax;
        $this->telephone = ($compte->telephone_bureau) ? $compte->telephone_bureau : $compte->telephone_mobile;
        
        return $this;
    }
    
    protected function createCompteSociete() {
        if($this->compte_societe) {
            return;
        }
        
        $compte = CompteClient::getInstance()->findOrCreateCompteSociete($this);
        $this->compte_societe = $compte->_id;
        $compte->nom = $this->raison_sociale;
        $compte->updateNomAAfficher();
        $compte->statut = $this->statut;
        $compte->addOrigine($this->_id);
	$this->addCompte($compte, -1 );
        return $compte;
    }
    
    protected function synchroAndSaveEtablissement() {
        if(($this->changedCooperative) || ($this->changedStatut)) {
	  foreach($this->getEtablissementsObj() as $id => $e) {
            $e->etablissement->cooperative = $this->cooperative;
            $e->etablissement->statut = $this->statut;
	    $e->etablissement->save(true);
	  }
	}
    }
    
    protected function synchroAndSaveCompte() {
        if($this->changedStatut) {
	  foreach($this->getComptesObj() as $id => $c) {
            $c->compte->statut = $this->statut;
	    $c->compte->save(true);
	  }
	}
    }
    
    public function getDateCreation() {
	$this->add('date_creation');
	return $this->_get('date_creation');
    }

    public function getDateModification() {
        $this->add('date_modification');
        return $this->_get('date_modification');
    }

    public function save($fromCompte = false) {
        $this->add('date_modification', date('Y-m-d'));
        if ($fromCompte) {
            return parent::save();
        }
        
        if (!$this->compte_societe) {
            $compte = $this->createCompteSociete();
            parent::save();
            $compte->save(true);
        }
        
        $this->synchroAndSaveEtablissement();
        $this->synchroAndSaveCompte();
	$this->changedCooperative = false;
	$this->changedStatut = false;
        return parent::save();
    }
        
}
