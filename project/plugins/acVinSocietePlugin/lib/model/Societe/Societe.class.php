<?php

/**
 * Model for Societe
 *
 */
class Societe extends BaseSociete {

    public function constructId() {
        $this->set('_id', 'SOCIETE-' . $this->identifiant);
    }

    public function createCompteSociete() {
        if (!$this->identifiant) {
            throw new sfException("La societe ne possède pas encore d'identifiant");
        }
        $contactSociete = CompteClient::getInstance()->createCompte($this);
        $contactSociete->setNom($this->raison_sociale);
        $this->compte_societe = $contactSociete->_id;
	return $contactSociete;
    }

    public function addNewContact() {
        $compte = CompteClient::getInstance()->createCompte($this->identifiant);
        $this->addCompte($compte,count(($this->contacts) + 1));
        return $compte;
    }

    public function addNewEtablissement() {
        $etablissement = EtablissementClient::getInstance()->createEtablissement($this->identifiant, $this->type_societe);
        $compteForEtb = CompteClient::getInstance()->createCompte($this->identifiant);
        $etablissement->compte = $compteForEtb->_id;
        $etablissement->save();
        $this->addEtablissement($etablissement,count(($this->etablissements) + 1));
        return $etablissement;
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
			$this->etablissements->add($e->_id)->ordre = $ordre;
		}
	}
		
    }

    public function addCompte($c, $ordre = null) {
      if (!$this->compte_societe) {
	$this->compte_societe = $c->_id;
      }
	if (!$this->contacts->exist($c->_id)) {
		$this->contacts->add($c->_id, array('nom' => $c->nom_a_afficher, 'ordre' => $ordre));
	}else{
		$this->contacts->add($c->_id)->nom = $c->nom_a_afficher;
		if ($ordre !== null) {
                        $this->contacts->add($c->_id)->ordre = $ordre;
                }
	}
    }

}
