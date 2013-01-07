<?php

/**
 * Model for Societe
 *
 */
class Societe extends BaseSociete {

  private $changedCooperative = null;

    public function constructId() {
        $this->set('_id', 'SOCIETE-' . $this->identifiant);
    }

    public function createCompteSociete() {
        if (!$this->identifiant) {
            throw new sfException("La societe ne possède pas encore d'identifiant");
        }
        $contactSociete = CompteClient::getInstance()->createCompte($this);
        $contactSociete->setNom($this->raison_sociale);
        $contactSociete->setAdresseSociete("1");
        $contactSociete->origines->add($this->identifiant,$this->identifiant);
        $contactSociete->save();
        $this->compte_societe = $contactSociete->_id;
	return $contactSociete;
    }

    public function addNewContact() {
        $compte = CompteClient::getInstance()->createCompte($this);
        $compte->save(true);
        return $compte;
    }

//    public function addNewEtablissement() {
//        $etablissement = EtablissementClient::getInstance()->createEtablissement($this);        
//        $compteForEtb = CompteClient::getInstance()->createCompte($this);
//        $compteForEtb->origines->add($etablissement->_id,$etablissement->_id);
//        $etablissement->compte = $compteForEtb->_id;
//        $compteForEtb->save();        
//        $etablissement->save(true);
//        $this->addEtablissement($etablissement,count(($this->etablissements) + 1));
//        return $etablissement;
//    }
    
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

    public function getIdFirstEtablissement() {       
        $ordre = 0;
        if(count($this->etablissements) <= 0) throw new sfException("Le premier établissement n'existe pas");        
        foreach ($this->etablissements as $id => $nom) {
            return substr(strstr($id,'-'),1);
        }
    }

    public function createEtablissement() {
        if ($this->canHaveChais()) {
            if (!$this->identifiant) {
                throw new sfException("La societe ne possède pas encore d'identifiant");
            }
            $etablissement = EtablissementClient::getInstance()->createEtablissement($this->identifiant, $this->type_societe);
            
            $this->addEtablissement($etablissement,count(($this->etablissements) + 1));
        }
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

    public function addEtablissement($e, $ordre = null) {
        if (! $this->etablissements->exist($e->_id)) {
                $this->etablissements->add($e->_id, array('nom' => $e->nom, 'ordre' => $ordre));
	} else {
		$this->etablissements->add($e->_id)->nom = $e->nom;
		if ($ordre !== null) {
		  $order = 0;
		}
		$this->etablissements->add($e->_id)->ordre = $ordre;
	}
	if ($e->compte) {
	  $this->addCompte($e->getContact(), $ordre);
	}
    }

    public function setCooperative($c) {
      $this->_set('cooperative', $c);
      foreach($this->getEtablissementsObj() as $e) {
	$e->cooperative = $c;
      }
      $this->changedCooperative = true;
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

    public function getCompte() {
        $comptes = CompteClient::getInstance()->find($this->compte_societe);
    }
    
    public function setCodesComptables($is_codes) {
        if(in_array(SocieteClient::NUMEROCOMPTE_TYPE_CLIENT, $is_codes))
                $this->code_comptable_client = '02'.$this->identifiant;        
        if(in_array(SocieteClient::NUMEROCOMPTE_TYPE_FOURNISSEUR, $is_codes))
                $this->code_comptable_fournisseur = '04'.$this->identifiant;
    }
    
    public function save($fromCompte = false) {
        if ($fromCompte) 
            return parent::save();
        
        $compte = null;
        if (!$this->compte_societe) {
            parent::save();
            $compte = CompteClient::getInstance()->createCompte($this);
            $compte->origines->add($this->_id,$this->_id);
            $compte->nom = $this->raison_sociale;
            $compte->nom_a_afficher = $this->raison_sociale;
            $compte->save(true);
            $this->compte_societe = $compte->_id;
	    $this->addCompte($compte, -1 );
        }
        if (!$compte) {
            $compte = $this->getCompte();
        }
        $compte->adresse = $this->siege->adresse;
        $compte->code_postal = $this->siege->code_postal;
        $compte->commune = $this->siege->commune;

        $compte->save(true);
	if ($this->changedCooperative) {
	  foreach($this->getEtablissementsObj() as $e) {
	    $e->save(true);
	  }
	}
	$this->changedCooperative = false;
	
        return parent::save();
    }
    
}
