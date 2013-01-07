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

    public function save($fromsociete = false, $frometablissement = false) {
        if (is_null($this->adresse_societe))
            $this->adresse_societe = (int) $fromsociete;

        foreach ($this->origines as $origine) {
            if (preg_match('/^ETABLISSEMENT-/', $origine) && !$frometablissement) {
                $etb = EtablissementClient::getInstance()->find($origine);
                $etb->siege->adresse = $this->adresse;
                $etb->siege->code_postal = $this->code_postal;
                $etb->siege->commune = $this->commune;
                $etb->save(false, true);
                $this->nom_a_afficher = $etb->nom;
                break;
            }
            if (preg_match('/^SOCIETE-/', $origine) && !$fromsociete) {
                $soc = SocieteClient::getInstance()->find($origine);
                $soc->siege->adresse = $this->adresse;
                $soc->siege->code_postal = $this->code_postal;
                $soc->siege->commune = $this->commune;
                $soc->save(true);
                $this->nom_a_afficher = $soc->raison_sociale;
            }
        }

	$this->compte_type = CompteClient::getInstance()->createTypeFromOrigines($this->origines);

        parent::save();
        if (!$fromsociete) {
            $soc = $this->getSociete();
            if (!$soc) {
                throw new sfException("Societe not found for " . $this->_id);
            }
            $soc->addCompte($this);
            $soc->save(true);
        }
    }

    public function isSocieteContact() {
        return ((SocieteClient::getInstance()->find($this->id_societe)->compte_societe) == $this->_id);
    }

    public function isEtablissementContact() {
        foreach ($this->origines as $origine) {
            if (preg_match('/^ETABLISSEMENT[-]{1}[0-9]*$/', $origine)) {
                return true;
            }
        }
        return false;
    }

    public function getSocieteOrigine() {
        foreach ($this->origines as $origine) {
            if (preg_match('/^SOCIETE[-]{1}[0-9]*$/', $origine)) {
                return $origine;
            }
        }
        return null;
    }

    public function getEtablissementOrigine() {
        foreach ($this->origines as $origine) {
            if (preg_match('/^ETABLISSEMENT[-]{1}[0-9]*$/', $origine)) {
                return $origine;
            }
        }
        return null;
    }

    public function updateFromEtablissement($e) {
      $this->nom = $e->nom;
      $this->email = $e->email;
      $this->adresse = $e->siege->adresse;
      $this->code_postal = $e->siege->code_postal;
      $this->commune = $e->siege->commune;
      $this->fax = $e->fax;
      $this->telephone_bureau = $e->telephone;
      $this->origines->add(null, $e->id_societe);
      $this->origines->add(null, 'ETABLISSEMENT-'.$e->identifiant);
      return $this;
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
        $this->majNomAAfficher($c, $this->prenom, $this->nom);
       return $this->_set('civilite', $c); 
    }

    public function setPrenom($p) {
        $this->majNomAAfficher($this->civilite, $p, $this->nom);
        return $this->_set('prenom', $p); 
    }

    public function setNom($n) {
        $this->majNomAAfficher($this->civilite, $this->prenom, $n);
        return $this->_set('nom', $n); 
    }

    public function getCompteType() {
      return CompteClient::getInstance()->createTypeFromOrigines($this->origines);
    }
    
    private function majNomAAfficher($c,$p,$n){
        $this->nom_a_afficher = $c.' '.$p.' '.$n;
    }

}
