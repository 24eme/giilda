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
      if (count($this->getRegionsViticoles()) > 1)
	throw new sfException("La societe ".$this->identifiant." est reliée des établissements de plusieurs régions viticoles, ce qui n'est pas permis");
      if (!count($this->getRegionsViticoles()))
	throw new sfException("La societe ".$this->identifiant." n'a pas de région viti :(");
      $regions = $this->getRegionsViticoles();
      return array_shift($regions);
    }

    private function getRegionsViticoles() {
      $regions = array();
      foreach($this->getEtablissementsObj() as $id => $e) {
	$regions[$e->etablissement->region] = $e->etablissement->region;
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
    
    public function setCodesComptables($is_codes) {
        if(in_array(SocieteClient::NUMEROCOMPTE_TYPE_CLIENT, $is_codes))
                $this->code_comptable_client = '02'.$this->identifiant;        
        if(in_array(SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR, $is_codes))
                $this->code_comptable_fournisseur = '04'.$this->identifiant;
    }
    
    public function isNegoOrViti() {
        return ($this->type_societe == SocieteClient::SUB_TYPE_VITICULTEUR)
        || ($this->type_societe == SocieteClient::SUB_TYPE_NEGOCIANT);
    }
    
    public function synchroFromCompte() {
        $compte = $this->getMasterCompte();
        
        if(!$compte) {
            
            throw new sfException("Pas de compte societe. Bizarre !");
        }
        
        $this->siege->adresse = $compte->adresse;
        $this->siege->code_postal = $compte->code_postal;
        $this->siege->commune = $compte->commune;
        
        return $this;
    }
    
    protected function createAndSaveCompte() {
        $compte = CompteClient::getInstance()->findOrCreateCompteSociete($this);
        $compte->nom = $this->raison_sociale;
        $compte->statut = $this->statut;
        $compte->addOrigine($this->_id);
        $compte->save(true);
        $this->compte_societe = $compte->_id;
	$this->addCompte($compte, -1 );
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
    


    public function save($fromCompte = false) {
        $this->add('date_modification', date('Y-m-d'));
        if ($fromCompte) {
            return parent::save();
        }
        
        $compte = null;
        
        if (!$this->compte_societe) {
            parent::save();
            $this->createAndSaveCompte();
        }
        
        $this->synchroAndSaveEtablissement();
        $this->synchroAndSaveCompte();
	$this->changedCooperative = false;
	$this->changedStatut = false;
        return parent::save();
    }
        
}
