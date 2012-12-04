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
        if (!$this->identifiant) {
            throw new sfException("La societe ne possède pas encore d'identifiant");
        }
        $contactSociete = CompteClient::getInstance()->createCompte($this->identifiant);
        $contactSociete->setNom($this->raison_sociale);
        $this->id_compte_societe = $contactSociete->_id;
    }

    public function addNewContact() {
        $compte = CompteClient::getInstance()->createCompte($this->identifiant);
        $this->addCompte($compte,count(($this->contacts) + 1));
        return $compte;
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
        if ($this->hasChais()) {
            if (!$this->identifiant) {
                throw new sfException("La societe ne possède pas encore d'identifiant");
            }
            $etablissement = EtablissementClient::getInstance()->createEtablissement($this->identifiant, $this->type_societe);
            
            $this->addEtablissement($etablissement,count(($this->etablissements) + 1));
        }
    }

    public function hasChais() {
        return in_array($this->type_societe, SocieteClient::getSocieteTypesWithChais());
    }

    public function getEtablissementsObj() {
        $etablissements = array();
        foreach ($this->etablissements as $id => $nom) {
            $ordre = strstr($id,'-',true);
            $idEtablissement = substr(strstr($id,'-'),1);
            $etablissements[$idEtablissement] = new stdClass();
            $etablissements[$idEtablissement]->etablissement =  EtablissementClient::getInstance()->find($idEtablissement);
            $etablissements[$idEtablissement]->ordre = $ordre;
        }
        return $etablissements;
    }

    public function addEtablissement($e, $ordre = 0) {
	$this->etablissements->add(sprintf('%02d-%s', $ordre, $e->_id), $e->nom);
    }
    
    public function addCompte($c, $ordre = 0) {
	$this->contacts->add(sprintf('%02d-%s', $ordre, $c->_id), $c->nom);
    }
   
}
