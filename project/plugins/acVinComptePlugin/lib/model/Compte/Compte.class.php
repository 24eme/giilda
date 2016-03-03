<?php

/**
 * Model for Compte
 *
 */
class Compte extends BaseCompte implements InterfaceCompteGenerique {

    public function constructId() {
        $this->set('_id', 'COMPTE-' . $this->identifiant);
    }

    public function getSociete() {
        return SocieteClient::getInstance()->find($this->id_societe);
    }

    public function getLogin() {
        return preg_replace("/^[0-9]{6}([0-9]+)$/", "", $this->identifiant);
    }

    public function getMasterCompte() {
        if ($this->isSameAdresseThanSociete()) {
            return $this->getSociete()->getContact();
        }
        return null;
    }

    public function isSameAdresseThanSociete() {

        return CompteGenerique::isSameAdresseComptes($this, $this->getSociete()->getContact());
    }

    public function hasCoordonneeInheritedFromSociete() {
        
        return $this->isSameAdresseThanSociete();
    }

    public function isSameContactThanSociete() {
       
       return CompteGenerique::isSameContactComptes($this, $this->getSociete()->getContact());
    }

    public function updateNomAAfficher() {
        if (!$this->nom) {
            return;
        }

        $this->nom_a_afficher = trim(sprintf('%s %s %s', $this->civilite, $this->prenom, $this->nom));
    }

    public static function transformTag($tag) {
        $tag = strtolower($tag);
        return preg_replace('/[^a-z0-9éàùèêëïç]+/', '_', $tag);
    }

    public function addTag($type, $tag) {
        $tags = $this->add('tags')->add($type)->toArray(true, false);
        $tags[] = Compte::transformTag($tag);
        $tags = array_unique($tags);
        $this->get('tags')->remove($type);
        $this->get('tags')->add($type, array_values($tags));
    }

    public function removeTag($type, $tags) {
        $tag = Compte::transformTag($tag);
        $tags_existant = $this->add('tags')->add($type)->toArray(true, false);

        $tags_existant = array_diff($tags_existant, $tags);
        $this->get('tags')->remove($type);
        $this->get('tags')->add($type, array_values($tags));
    }

    public function removeTags($type, $tags) {
        foreach ($tags as $k => $tag)
            $tags[$k] = Compte::transformTag($tag);

        $tags_existant = $this->add('tags')->add($type)->toArray(true, false);

        $tags_existant = array_diff($tags_existant, $tags);
        $this->get('tags')->remove($type);
        $this->get('tags')->add($type, array_values($tags_existant));
    }

    public function addOrigine($id) {
        if (!in_array($id, $this->origines->toArray(false))) {
            $this->origines->add(null, $id);
        }
    }

    public function removeOrigine($id) {
        if (!in_array($id, $this->origines->toArray(false))) {
            return;
        }
        foreach ($this->origines->toArray(false) as $key => $o) {
            if ($o == $id) {
                $this->origines->remove($key);
                return;
            }
        }
    }

    public function hasOrigine($id) {
        foreach ($this->origines as $origine) {
            if ($id == $origine) {
                return true;
            }
        }
        return false;
    }

    public function save() {

        if ($this->isSocieteContact()) {
            $this->addTag('automatique', 'Societe');
        }

        $this->tags->remove('automatique');
        $this->tags->add('automatique');
        if ($this->exist('teledeclaration_active') && $this->teledeclaration_active) {
            if ($this->hasDroit(Roles::TELEDECLARATION_VRAC)) {
                $this->addTag('automatique', 'teledeclaration_active');
            }
        }

        $this->compte_type = CompteClient::getInstance()->createTypeFromOrigines($this->origines);

        $societe = $this->getSociete();
        if ($this->isSocieteContact()) {
            $this->addTag('automatique', 'Societe');
            $this->addTag('automatique', $societe->type_societe);
            if ($this->getFournisseurs()) {
                $this->removeFournisseursTag();
                foreach ($this->getFournisseurs() as $type_fournisseur) {
                    $this->addTag('automatique', $type_fournisseur);
                }
            }
        }

        if ($this->exist('teledeclaration_active') && $this->teledeclaration_active) {
            if ($this->hasDroit(Roles::TELEDECLARATION_VRAC)) {
                $this->addTag('automatique', 'teledeclaration_active');
            }
        }

        if ($this->isEtablissementContact()) {
            $this->addTag('automatique', 'Etablissement');
            $this->addTag('automatique', $this->getEtablissement()->famille);
        }
        if (!$this->isEtablissementContact() && !$this->isSocieteContact()) {
            $this->addTag('automatique', 'Interlocuteur');
        }

        $this->compte_type = CompteClient::getInstance()->createTypeFromOrigines($this->origines);

        $this->updateNomAAfficher();

        $this->societe_informations->type = $societe->type_societe;
        $this->societe_informations->raison_sociale = $societe->raison_sociale;
        $this->societe_informations->adresse = $societe->siege->adresse;
        $this->societe_informations->adresse_complementaire = $societe->siege->adresse_complementaire;
        $this->societe_informations->code_postal = $societe->siege->code_postal;
        $this->societe_informations->commune = $societe->siege->commune;
        $this->societe_informations->email = $societe->email;
        $this->societe_informations->telephone = $societe->telephone;
        $this->societe_informations->fax = $societe->fax;

        $new = $this->isNew();

        parent::save();

        if ($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR && $new) {
            $societe->addCompte($this);
            $societe->save();
        }

        $this->autoUpdateLdap();
    }

    public function isSocieteContact() {
        return ((SocieteClient::getInstance()->find($this->id_societe)->compte_societe) == $this->_id);
    }

    private function removeFournisseursTag() {
        $this->removeTags('automatique', array('Fournisseur', 'MDV', 'PLV'));
    }

    public function getFournisseurs() {
        $societe = SocieteClient::getInstance()->find($this->id_societe);
        if (!$societe->code_comptable_fournisseur)
            return false;

        $fournisseurs = array('Fournisseur');
        if ($societe->exist('type_fournisseur') && count($societe->type_fournisseur->toArray(true, false))) {
            $fournisseurs = array_merge($fournisseurs, $societe->type_fournisseur->toArray(true, false));
        }
        return $fournisseurs;
    }

    public function isEtablissementContact() {

        return $this->getEtablissementOrigine() != null;
    }

    public function getEtablissement() {

        if (!$this->getEtablissementOrigine()) {

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

    public function getStatutTeledeclarant() {
        if (preg_match("{TEXT}", $this->mot_de_passe)) {

            return CompteClient::STATUT_TELEDECLARANT_NOUVEAU;
        }

        if (preg_match("{OUBLIE}", $this->mot_de_passe)) {

            return CompteClient::STATUT_TELEDECLARANT_OUBLIE;
        }

        if (preg_match("{SSHA}", $this->mot_de_passe)) {

            return CompteClient::STATUT_TELEDECLARANT_INSCRIT;
        }

        return CompteClient::STATUT_TELEDECLARANT_INACTIF;
    }

    /**
     *
     * @param string $mot_de_passe
     */
    public function setMotDePasseSSHA($mot_de_passe) {
        mt_srand((double) microtime() * 1000000);
        $salt = pack("CCCC", mt_rand(), mt_rand(), mt_rand(), mt_rand());
        $hash = "{SSHA}" . base64_encode(pack("H*", sha1($mot_de_passe . $salt)) . $salt);
        $this->_set('mot_de_passe', $hash);
    }

    public function isActif() {
        return ($this->statut == CompteClient::STATUT_ACTIF);
    }

    public function isSuspendu() {
        
        return $this->statut && ($this->statut == CompteClient::STATUT_SUSPENDU);
     }

    public function autoUpdateLdap($verbose = 0) {
        if (sfConfig::get('app_ldap_autoupdate', false)) {
            return $this->updateLdap($verbose);
        }
        return;
    }

    public function updateLdap($verbose = 0) {
        $ldap = new CompteLdap();
        if ($this->isActif())
            $ldap->saveCompte($this, $verbose);
        else
            $ldap->deleteCompte($this, $verbose);
    }

    public function buildDroits($removeAll = false) {
        if ((!$this->exist('type_societe') || !$this->type_societe) && (!$this->exist('id_societe') || !$this->id_societe)) {
            throw new sfException("Aucun type de société les droits ne sont pas enregistrables");
        }
        if ($removeAll && $this->exist('droits') && $this->droits) {
            $this->remove('droits');
        }
        $droits = $this->add('droits');
        $acces_teledeclaration = false;

        $type_societe = ($this->exist('type_societe') && $this->type_societe) ? $this->type_societe : null;
        if (!$type_societe) {
            $type_societe = $this->getSociete()->getTypeSociete();
        }

        if ($type_societe == SocieteClient::TYPE_OPERATEUR) {
            $acces_teledeclaration = true;
            $droits->add(Roles::TELEDECLARATION_VRAC, Roles::TELEDECLARATION_VRAC);
        }


        if ($acces_teledeclaration) {
            $droits->add(Roles::TELEDECLARATION, Roles::TELEDECLARATION);
        }
    }

    public function hasDroit($droit) {
        $droits = $this->get('droits')->toArray(0, 1);
        return in_array($droit, $droits);
    }

    public function getDroits() {

        return $this->_get('droits');
    }

    public function isTeledeclarationActive() {
        return ($this->exist('teledeclaration_active') && $this->teledeclaration_active);
    }

    public function addCommentaire($s) {
        $c = $this->get('commentaire');
        if ($c) {
            return $this->_set('commentaire', $c . "\n" . $s);
        }
        return $this->_set('commentaire', $s);
    }

    public function setAdresse($a) {
        $this->_set('adresse', $a);
        return $this;
    }

    public function setAdresseComplementaire($ac) {
        $this->_set('adresse_complementaire', $ac);
        return $this;
    }

    public function setCommune($c) {
        $this->_set('commune', $c);
        return $this;
    }

    public function setCodePostal($c) {
        $this->_set('code_postal', $c);
        return $this;
    }

    public function setPays($p) {
        $this->_set('pays', $p);
        return $this;
    }

    public function setSiteInternet($s) {
        $this->_set('site_internet', $s);
        return $this;
    }

    public function setTelephone($phone) {
        $this->_set('telephone_bureau', $phone);
        return $this;
    }

    public function setTelephonePerso($phone) {
        $this->_set('telephone_perso', $phone);
        return $this;
    }

    public function setTelephoneMobile($phone) {

        $this->_set('telephone_mobile', $phone);
        return $this;
    }

    public function setTelephoneBureau($phone) {

        $this->_set('telephone_bureau', $phone);
        return $this;
    }

    public function setFax($fax) {

        $this->_set('fax', $fax);
        return $this;
    }

    public function setEmail($email) {

        $this->_set('email', $email);
        return $this;
    }

    public function getSiteInternet() {
        return $this->_get('site_internet');
    }

    public function getTelephone() {
        return $this->_get('telephone_bureau');
    }

    public function getAdresse() {
        return $this->_get('adresse');
    }

    public function getAdresseComplementaire() {
        return $this->_get('adresse_complementaire');
    }

    public function getCommune() {
        return $this->_get('commune');
    }

    public function getCodePostal() {
        return $this->_get('code_postal');
    }

    public function getPays() {
        return $this->_get('pays');
    }

    public function getEmail() {
        return $this->_get('email');
    }

    public function getTelephoneBureau() {
        return $this->_get('telephone_bureau');
    }

    public function getTelephonePerso() {
        return $this->_get('telephone_perso');
    }

    public function getTelephoneMobile() {
        return $this->_get('telephone_mobile');
    }

    public function getFax() {
        return $this->_get('fax');
    }

}
