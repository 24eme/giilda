<?php

/**
 * Model for Societe
 *
 */
class Societe extends BaseSociete {

    public function constructId() {
        $this->set('_id', 'SOCIETE-' . $this->identifiant);
    }

    
    public function setContactSociete() {
        if(!$this->identifiant)
            {
            throw new sfException("La societe ne possède pas encore d'identifiant");
        }
        $contactSociete = CompteClient::getInstance()->createCompte($this->identifiant);
        $this->id_compte_societe = $contactSociete->_id;
        
    }
    
    public function setFirstChai() {
        if($this->hasChais()) {
            if(!$this->identifiant)
            {
                throw new sfException("La societe ne possède pas encore d'identifiant");
            }
           $etablissement = EtablissementClient::getInstance()->createEtablissement($this->identifiant,$this->type_societe);
           $chai = $this->etablissements->add(count($this->etablissements));
           $chai->initChai($etablissement->_id);
        }
    }
    
    public function hasChais() {
        return in_array($this->type_societe, SocieteClient::getSocieteTypesWithChais());
    }
    
    public function getEtablissementsObj() {
        $etablissements = array();
        foreach ($this->etablissements as $etablissement) {
            $etablissements[$etablissement->ordre] = EtablissementClient::getInstance()->find($etablissement->id_etablissement);

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

    public function addContact($c, $ordre = null) {
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
