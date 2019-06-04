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

    public function getLogin() {
        return preg_replace("/^[0-9]{6}([0-9]+)$/", "", $this->identifiant);
    }

    public function getMasterCompte() {
        if ($this->isSameCoordonneeThanSociete()) {

            return $this->getSociete()->getContact();
        }

        return null;
    }

    public function isSameCoordonneeThanSociete() {

        return $this->getSociete()->getContact()->hasOrigine($this->_id);
    }

    public function doSameCoordonneeThanSocieteAndSave($value) {
        if ($value && $this->isSameCoordonneeThanSociete()) {

            return;
        }

        if (!$value && !$this->isSameCoordonneeThanSociete()) {

            return;
        }

        $compte = $this->getSociete()->getContact();

        if ($value) {
            $compte->addOrigine($this->_id);
        } else {
            $compte->removeOrigine($this->_id);
        }

        $compte->save(false, false, true);

        $this->synchroFromCompte();
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
        if ($this->isSocieteContact()) {

            return $this->updateFromSociete();
        }

        if ($this->isEtablissementContact()) {

            return $this->updateFromEtablissement();
        }
    }

    protected function updateFromSociete() {
        $societe = $this->getSociete();

        if (!$societe) {
            return;
        }

        if (sfConfig::get('sf_logging_enabled')) {
            sfContext::getInstance()->getLogger()->log(sprintf("{Contact} synchro du compte %s à partir de la societe %s", $this->_id, $societe->_id));
        }

        $this->nom = $societe->raison_sociale;
    }

    protected function updateFromEtablissement() {
        $etablissement = $this->getEtablissement();

        if (!$etablissement) {
            return;
        }

        if (sfConfig::get('sf_logging_enabled')) {
            sfContext::getInstance()->getLogger()->log(sprintf("{Contact} synchro du compte %s à partir de l'etablissement %s", $this->_id, $etablissement->_id));
        }

        $this->nom = ($etablissement->nom) ? $etablissement->nom : $etablissement->raison_sociale;
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

    protected function synchroAndSaveSociete() {
        $soc = $this->getSociete();
        if (!$soc) {
            throw new sfException("Societe not found for " . $this->_id);
        }
        $soc->addCompte($this);
        $soc->save(true);
    }

    public function save($fromsociete = false, $frometablissement = false, $from_compte = false, $from_task = false) {

        if ($from_task) {
            parent::save();
            $this->autoUpdateLdap();
            return;
        }
        $this->tags->remove('automatique');
        $this->tags->add('automatique');

        if ($this->isSocieteContact()) {
            $this->addTag('automatique', 'Societe');
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
        }
        if (!$this->isEtablissementContact() && !$this->isSocieteContact()) {
            $this->addTag('automatique', 'Interlocuteur');
        }

        $societe = $this->getSociete();
        $this->addTag('automatique', $societe->type_societe);

        if (is_null($this->adresse_societe)) {
            $this->adresse_societe = (int) $fromsociete;
        }
        $this->compte_type = CompteClient::getInstance()->createTypeFromOrigines($this->origines);

        $this->synchro();
        $this->updateNomAAfficher();

        parent::save();
        $this->autoUpdateLdap();

        if (!$fromsociete) {
            $this->synchroAndSaveSociete();
        }

        foreach ($this->origines as $origine) {
            $doc = acCouchdbManager::getClient()->find($origine);
            if ($doc->type == 'Etablissement' && !$frometablissement) {
                $doc->synchroFromCompte();
                $doc->save($fromsociete, false, $from_compte);
            }

            if ($doc->type == 'Societe' && !$fromsociete) {
                $doc->synchroFromCompte();
                $doc->save();
            }

            if ($doc->type == 'Compte' && !$from_compte) {
                $doc->synchroFromCompte();
                $doc->save();
            }
        }
    }

    public function synchroFromSociete($societe = null) {
        if (!$societe) {
            $societe = $this->getSociete();
        }

        if (!$societe) {

            return;
        }

        $this->remove('raison_sociale_societe');
        $this->remove('type_societe');
        $this->societe_informations->type = $societe->type_societe;
        $this->societe_informations->raison_sociale = $societe->raison_sociale;
        $this->societe_informations->adresse = $societe->siege->adresse;
        $this->societe_informations->adresse_complementaire = "";
        if ($societe->siege->exist("adresse_complementaire")) {
            $this->societe_informations->adresse_complementaire = $societe->siege->adresse_complementaire;
        }
        $this->societe_informations->code_postal = $societe->siege->code_postal;
        $this->societe_informations->commune = $societe->siege->commune;
        $this->societe_informations->email = $societe->email;
        $this->societe_informations->telephone = $societe->telephone;
        $this->societe_informations->fax = $societe->fax;
    }

    public function synchroFromCompte() {
        $compte = $this->getMasterCompte();

        if (!$compte) {

            return null;
        }

        $this->adresse = $compte->adresse;
        $this->adresse_complementaire = $compte->adresse_complementaire;
        $this->code_postal = $compte->code_postal;
        $this->commune = $compte->commune;
        $this->cedex = $compte->cedex;
        $this->pays = $compte->pays;
        $this->email = $compte->email;
        $this->fax = $compte->fax;
        $this->telephone_bureau = $compte->telephone_bureau;
        $this->telephone_mobile = $compte->telephone_mobile;

        return $this;
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

    public function setEmail($email) {
        if ($email == "") {
            return $this->_set('email', "");
        }
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

    public function updateAndSaveCoordoneesFromEtablissement($etablissement) {
        $this->adresse = $etablissement->siege->adresse;
        $this->adresse_complementaire = ($etablissement->siege->exist('adresse_complementaire')) ? $etablissement->siege->adresse_complementaire : "";
        $this->code_postal = $etablissement->siege->code_postal;
        $this->commune = $etablissement->siege->commune;
        $this->save(true, true);
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

        if ($type_societe != SocieteClient::SUB_TYPE_COURTIER) {
            $acces_teledeclaration = true;
            $droits->add(Roles::OBSERVATOIRE, Roles::OBSERVATOIRE);
        }
        if (($type_societe == SocieteClient::SUB_TYPE_NEGOCIANT) || ($type_societe == SocieteClient::SUB_TYPE_COURTIER)) {
            $acces_teledeclaration = true;
            $droits->add(Roles::TELEDECLARATION_VRAC, Roles::TELEDECLARATION_VRAC);
            $droits->add(Roles::TELEDECLARATION_VRAC_CREATION, Roles::TELEDECLARATION_VRAC_CREATION);
            $droits->add(Roles::TELEDECLARATION_DRM_ACQUITTE, Roles::TELEDECLARATION_DRM_ACQUITTE);
        }

        if ($type_societe == SocieteClient::SUB_TYPE_VITICULTEUR) {
            $acces_teledeclaration = true;
            $droits->add(Roles::TELEDECLARATION_VRAC, Roles::TELEDECLARATION_VRAC);
        }

        if ($acces_teledeclaration) {
            $droits->add(Roles::TELEDECLARATION, Roles::TELEDECLARATION);
        }
    }

    public function hasDroit($droit) {
	if (! $this->exist('droits')) {
		return false;
	}
        $droits = $this->get('droits')->toArray(0,1);
        return in_array($droit, $droits);
    }

    public function getDroits() {

        return $this->_get('droits');
    }

    public function updateDroits($droits) {
        $this->remove('droits');
        $compteDroits = $this->getOrAdd('droits');
        $type_societe = ($this->exist('type_societe') && $this->type_societe) ? $this->type_societe : null;
        if (!$type_societe) {
            $type_societe = $this->getSociete()->getTypeSociete();
        }

        $acces_teledeclaration = false;
        foreach ($droits as $droit) {
            $acces_teledeclaration = true;
            if ($droit == Roles::OBSERVATOIRE) {
                $compteDroits->add(Roles::OBSERVATOIRE, Roles::OBSERVATOIRE);
            }
            if ($droit == Roles::CONTRAT) {
                $compteDroits->add(Roles::TELEDECLARATION_VRAC, Roles::TELEDECLARATION_VRAC);
                if (($type_societe == SocieteClient::SUB_TYPE_NEGOCIANT) || ($type_societe == SocieteClient::SUB_TYPE_COURTIER)) {
                    $compteDroits->add(Roles::TELEDECLARATION_VRAC_CREATION, Roles::TELEDECLARATION_VRAC_CREATION);
                }
            }
            $compteDroitsArray = $compteDroits->toArray(0,1);
            if ($droit == Roles::DRM) {
              if(!in_array(Roles::TELEDECLARATION_DRM, $compteDroitsArray)){
                $compteDroits->add(Roles::TELEDECLARATION_DRM, Roles::TELEDECLARATION_DRM);
              }
            }
            if ($droit == Roles::TELEDECLARATION_DRM_ACQUITTE) {
                $compteDroits->add(Roles::TELEDECLARATION_DRM_ACQUITTE, Roles::TELEDECLARATION_DRM_ACQUITTE);
            }
            if ($droit == Roles::TELEDECLARATION_DOUANE) {
                if(!in_array(Roles::TELEDECLARATION_DRM, $compteDroitsArray)){
                  $compteDroits->add(Roles::TELEDECLARATION_DRM, Roles::TELEDECLARATION_DRM);
                }
                if(!in_array(Roles::TELEDECLARATION_DOUANE, $compteDroitsArray)){
                  $compteDroits->add(Roles::TELEDECLARATION_DOUANE, Roles::TELEDECLARATION_DOUANE);
                }
            }

            if ($droit == Roles::TELEDECLARATION_FACTURE) {
                $compteDroits->add(Roles::TELEDECLARATION_FACTURE, Roles::TELEDECLARATION_FACTURE);
            }
            if ($droit == Roles::TELEDECLARATION_FACTURE_EMAIL) {
                $compteDroits->add(Roles::TELEDECLARATION_FACTURE_EMAIL, Roles::TELEDECLARATION_FACTURE_EMAIL);
            }

        }
        if($acces_teledeclaration){
            $compteDroits->add(Roles::TELEDECLARATION, Roles::TELEDECLARATION);
        }
    }

    public function isTeledeclarationActive() {
        return ($this->exist('teledeclaration_active') && $this->teledeclaration_active);
    }

    public function cleanDroits(){
      $compteDroits = $this->getOrAdd('droits');
      $droitsToKeep = array();
      $needToClean = false;
      foreach ($compteDroits as $index => $compteDroit) {
        if(!in_array($compteDroit,$droitsToKeep)){
          $droitsToKeep[] = $compteDroit;
        }else{
          $needToClean = true;
        }
      }
      if($needToClean){
        $this->remove('droits');
        $compteDroits = $this->add('droits',$droitsToKeep);
      }
      return $needToClean;
    }

}
