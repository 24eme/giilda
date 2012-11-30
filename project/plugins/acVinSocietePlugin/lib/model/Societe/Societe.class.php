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
   
}