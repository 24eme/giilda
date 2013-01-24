<?php

/**
 * Model for Compte
 *
 */
class Compte extends BaseCompte {

    public function constructId() {
        $this->set('_id', 'COMPTE-' . $this->identifiant);
    }

    public function getSociete() {
        return SocieteClient::getInstance()->find($this->id_societe);
    }

    public function setIdSociete($id) {
        $soc = SocieteClient::getInstance()->find($id);
        if (!$soc) {
            $identifiant = str_replace('SOCIETE-', '', $id);
            if (empty($identifiant))
                throw new sfException("Pas de société trouvée pour $id");
            return $this->_set('id_societe', $id);
        }
        $soc->addCompte($this);
        return $this->_set('id_societe', $soc->_id);
    }
    
    public function synchro() {
        if($this->isSocieteContact()) {
            
            return $this->updateFromSociete();
        }
        
        if($this->isEtablissementContact()) {
            
            return $this->updateFromEtablissement();
        }
        
        return $this->updateFromNothing();
    }
    
    protected function updateFromSociete() {
         $societe = $this->getSociete();
         
         if(!$societe) {
            return; 
         }
         
         if (sfConfig::get('sf_logging_enabled')) {
            sfContext::getInstance()->getLogger()->log(sprintf("{Contact} synchro du compte %s à partir de la societe %s", $this->_id, $societe->_id));
         }
         
         $this->nom_a_afficher = $societe->raison_sociale;
    }
    
    
    protected function updateFromEtablissement() {
         $etablissement = $this->getEtablissement();
         
         if(!$etablissement) {
            return; 
         }
         
         if (sfConfig::get('sf_logging_enabled')) {
            sfContext::getInstance()->getLogger()->log(sprintf("{Contact} synchro du compte %s à partir de l'etablissement %s", $this->_id, $etablissement->_id));
         }
         
         $this->nom_a_afficher = $etablissement->nom;
    }
    
    protected function updateFromNothing() {
        $this->nom_a_afficher = sprintf('%s %s %s', $this->civilite, $this->prenom, $this->nom);
    }
    
    public function addOrigine($id) {
        if(!in_array($id, $this->origines->toArray(false))) {
            $this->origines->add(null, $id);
        }
    }
    
    protected function synchroAndSaveSociete() {
        $soc = $this->getSociete();
        if (!$soc) {
            throw new sfException("Societe not found for " . $this->_id);
        }
        $soc->addCompte($this);
        $soc->save(true);
    }
    
    protected function synchroOrigines() {
        $is_etablissement_contact = $this->isEtablissementContact();
        $etablissement = $this->getEtablissement();
        
        $this->remove('origines');
        $this->add('origines');
        if ($this->isSocieteContact()) {
            $this->addOrigine($this->id_societe);
        }
        
        if ($is_etablissement_contact) {
            if($etablissement->compte == 'COMPTE-'.$this->identifiant) {
                $this->addOrigine($etablissement->_id);
            }
        }
    }

    public function save($fromsociete = false, $frometablissement = false) {
        if (is_null($this->adresse_societe)) {
            $this->adresse_societe = (int) $fromsociete;
        }
	$this->compte_type = CompteClient::getInstance()->createTypeFromOrigines($this->origines);
        $this->synchroOrigines();
        $this->synchro();

        parent::save();

        if (!$fromsociete) {
            $this->synchroAndSaveSociete();
        }
        
        foreach ($this->origines as $origine) {
            $doc = acCouchdbManager::getClient()->find($origine);
            if($doc->type == 'Etablissement' && !$frometablissement) {
                $doc->synchroFromCompte();
                $doc->save();
            }
            
            if($doc->type == 'Societe' && !$fromsociete) {
                $doc->synchroFromCompte();
                $doc->save();
            }
        }
    }
    
    public function isSocieteContact() {
        
        return ((SocieteClient::getInstance()->find($this->id_societe)->compte_societe) == $this->_id);
    }

    public function isEtablissementContact() {

        return $this->getEtablissementOrigine() != null;
    }
    
    public function getEtablissement() {
        
        if(!$this->getEtablissementOrigine()) {
            
            return null;
        }
        
        return EtablissementClient::getInstance()->find($this->getEtablissementOrigine());
    }

    public function getEtablissementOrigine() {
        foreach ($this->origines as $origine) {
            if (preg_match('/^ETABLISSEMENT[-]{1}[0-9]*$/', $origine)) {
                return $origine;
            }
        }
        return null;
    }

    public function updateWithAdresseSociete() {
        $soc = $this->getSociete();
        $compteSociete = CompteClient::getInstance()->find($soc->compte_societe);
        $this->commune = $compteSociete->commune;
        $this->cedex = $compteSociete->cedex;
        $this->code_postal = $compteSociete->code_postal;
        $this->adresse = $compteSociete->adresse;
        $this->adresse_complementaire = $compteSociete->adresse_complementaire;
        $this->pays = $compteSociete->pays;
    }

    public function setEmail($email) {
        if (preg_match('/^ *$/', $email)) {
            return;
        }
        return $this->_set('email', $email);
    }

    public function setCivilite($c) {
      
       return $this->_set('civilite', $c); 
    }

    public function setPrenom($p) {
        
        return $this->_set('prenom', $p); 
    }

    public function setNom($n) {
       
        return $this->_set('nom', $n); 
    }

    public function getCompteType() {
      return CompteClient::getInstance()->createTypeFromOrigines($this->origines);
    }

}
