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

    public function save($fromsociete = false) {
        if (is_null($this->adresse_societe))
            $this->adresse_societe = (int) $fromsociete;

        foreach ($this->origines as $origine) {
            if (preg_match('/^ETABLISSEMENT-/', $origine)) {
                $etb = EtablissementClient::getInstance()->find($origine);
                $etb->siege->adresse = $this->adresse;
                $etb->siege->code_postal = $this->code_postal;
                $etb->siege->commune = $this->commune;
                $etb->save();
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

    public function getType() {
      return CompteClient::getInstance()->createTypeFromOrigines($this->origines);
    }
    
    private function majNomAAfficher($c,$p,$n){
        $this->nom_a_afficher = $c.' '.$p.' '.$n;
    }
}
