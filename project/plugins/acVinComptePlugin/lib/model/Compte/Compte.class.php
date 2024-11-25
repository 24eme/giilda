<?php

/**
 * Model for Compte
 *
 */
class Compte extends BaseCompte implements InterfaceCompteGenerique {

    private $societe = NULL;
    private $cacheEtablissement;

    public function constructId() {
        $this->set('_id', 'COMPTE-' . $this->identifiant);
    }
    public function setSociete($s) {
      $this->societe = $s;
      $this->id_societe = $s->_id;
    }
    public function getSociete() {
        if (!$this->societe) {
          $this->societe = SocieteClient::getInstance()->findSingleton($this->id_societe);
        }
        return $this->societe;
    }

    public function getLibelleWithAdresse() {
        $libelle = $this->nom_a_afficher;
        if ($this->adresse || $this->adresse_complementaire || $this->code_postal || $this->commune || $this->pays) {
            $libelle .= ' —';
        }
        if ($this->adresse) {
            $libelle .= ' '.$this->adresse;
        }
        if ($this->adresse_complementaire) {
            $libelle .=  ' '.$this->adresse_complementaire;
        }
        if ($this->code_postal) {
            $libelle .= ' '.$this->code_postal;
        }
        if ($this->commune) {
            $libelle .= ' '.$this->commune;
        }
        if ($this->pays) {
        	 $libelle .= ' ('.$this->pays.')';
        }
        return $libelle;
    }

    public function getMasterCompte() {

        return $this;
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

    public function isSameDroitsThanSociete() {

       return Compte::isSameDroitsComptes($this, $this->getSociete()->getContact());
    }

    public function isSameExtrasThanSociete() {

       return Compte::isSameExtrasComptes($this, $this->getSociete()->getContact());
    }

    public function updateNomAAfficher() {
        if (!$this->nom) {
            return;
        }
        if($this->isSocieteContact()){
            $this->nom_a_afficher = trim(sprintf('%s', $this->nom));
            return;
        }
        if($this->isEtablissementContact()){
            $this->nom_a_afficher = trim(sprintf('%s', $this->nom));
            return;
        }
        $this->nom_a_afficher = trim(sprintf('%s %s %s', $this->civilite, $this->prenom, $this->nom));
    }

    public function getCodeCreation() {
        if(strpos("{TEXT}") === false) {
            return null;
        }

        return str_replace("{TEXT}","", $this->mot_de_passe);
    }

    public static function transformTag($tag) {
        $tag = strtolower(KeyInflector::unaccent($tag));
        return preg_replace('/[^a-z0-9éàùèêëïç]+/', '_', $tag);
    }

    public function updateTagsGroupes() {
        $this->tags->remove('groupes');
        $this->tags->add('groupes');
        foreach($this->groupes as $groupe_obj) {
            $this->addTag('groupes', $groupe_obj->nom);
        }
    }

    public function addInGroupes($grp,$fct){
        $grp = preg_replace('/^ */', '', preg_replace('/ *$/', '', $grp));
        $allGrps = $this->getOrAdd('groupes');
        $grpNode = $allGrps->add();
        $grpNode->nom = $grp;
        $grpNode->fonction = $fct;
    }

    public function removeGroupes($grp){
        $grp = preg_replace('/^ */', '', preg_replace('/ *$/', '', $grp));
        $allGrps = $this->getOrAdd('groupes');
        $grp_to_keep = array();
        foreach ($allGrps as $oldGrp) {
          if($oldGrp->nom != $grp){
            $grp_to_keep[] = $oldGrp;
          }
        }
        $this->remove("groupes");
        $this->getOrAdd('groupes');
        foreach ($grp_to_keep as $newgrp) {
          $this->groupes->add(null,$newgrp);
        }

    }

    public function getGroupesSortedNom(){
      $gs = $this->groupes->toArray(1,0);
      uasort($gs, "CompteClient::sortGroupes");
      return $gs;
    }

    public function addTag($type, $tag) {
        $tags = $this->add('tags')->add($type)->toArray(true, false);
        $tags[] = Compte::transformTag($tag);
        $tags = array_unique($tags);
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
        if(SocieteConfiguration::getInstance()->isDisableSave()) {

            throw new Exception("L'enregistrement des sociétés, des établissements et des comptes sont désactivés");
        }
        // Pour le CIVA on prend comme login l'identifiant d'établissement (la plupart du temps le CVI)
        if(SocieteConfiguration::getInstance()->isIdentifiantEtablissementSaisi() && $this->isSocieteContact() && !$this->exist('login') && $this->getStatutTeledeclarant() == CompteClient::STATUT_TELEDECLARANT_NOUVEAU && $this->getSociete()->getEtablissementPrincipal()) {
            $this->add('login', $this->getSociete()->getEtablissementPrincipal()->identifiant);
        }

        $this->tags->remove('automatique');
        $this->tags->add('automatique');
        if ($this->exist('teledeclaration_active') && $this->teledeclaration_active) {
            if ($this->hasDroit(Roles::TELEDECLARATION_VRAC)) {
                $this->addTag('automatique', 'teledeclaration_active');
            }
        }
        if ($this->exist('region') && $this->region) {
            $this->addTag('automatique', $this->region);
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
            $this->etablissement_informations->cvi = $this->getEtablissement()->cvi;
            $this->etablissement_informations->ppm = $this->getEtablissement()->ppm;
            $this->add('region', $this->getEtablissement()->region);
        } elseif ($this->isSocieteContact()) {
            $cvis = array();
            $ppms = array();
            $regions = array();
            foreach ($this->getSociete()->getEtablissementsObj() as $etb) {
                $cvis[] = $etb->etablissement->cvi;
                $ppms[] = $etb->etablissement->ppm;
                $regions[] = $etb->etablissement->region;
                $this->addTag('automatique', $etb->etablissement->famille);
            }
            $this->etablissement_informations->cvi = implode('|', $cvis);
            $this->etablissement_informations->ppm = implode('|', $ppms);
            if($societe->exist('region') && $societe->region) {
                $this->add('region', $societe->region);
            } else {
                $this->add('region', implode('|', array_unique($regions)));
            }
        }else{
            $this->etablissement_informations->cvi = null;
            $this->etablissement_informations->ppm = null;
            $this->add('region', null);
        }
        if (!$this->isEtablissementContact() && !$this->isSocieteContact()) {
            $this->addTag('automatique', 'Interlocuteur');
        }

        $this->compte_type = CompteClient::getInstance()->createTypeFromOrigines($this->origines);
        $this->interpro = "INTERPRO-declaration";

        $this->updateNomAAfficher();

        $this->societe_informations->type = $societe->type_societe;
        $this->societe_informations->raison_sociale = $societe->raison_sociale;
        $this->societe_informations->adresse = $societe->siege->adresse;
        $this->societe_informations->adresse_complementaire = $societe->siege->adresse_complementaire;
        $this->societe_informations->code_postal = $societe->siege->code_postal;
        $this->societe_informations->commune = $societe->siege->commune;
        $this->societe_informations->email = $societe->email;
        $this->societe_informations->siret = $societe->siret;

        $this->societe_informations->fax = $societe->fax;

        $this->updateExtras();
        $new = $this->isNew();

        if($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR && $this->isSameAdresseThanSociete()) {
            CompteGenerique::pullAdresse($this, $societe->getMasterCompte());
        }

        if($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR && $this->isSameContactThanSociete()) {
            CompteGenerique::pullContact($this, $societe->getMasterCompte());
        }

        if($this->exist('en_alerte') && $this->en_alerte){
            $this->addTag('automatique', 'en_alerte');
        }

        if ($this->exist('droits') && !count($this->droits)) {
            $this->remove('droits');
        }

        if (!$this->isSocieteContact() && self::isSameDroitsComptes($this, $societe->getMasterCompte())) {
            $this->remove('droits');
        }

        $this->tags->remove('droits');

        if ($this->exist('droits')) {
            $this->tags->add('droits');
            foreach ($this->droits as $droit) {
                $this->addTag('droits', $droit);
                $this->addTag('droits', preg_replace('/:.*/', '', $droit));
            }
        }

        if ($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR && $this->fonction) {
            $this->addTag('automatique', $this->fonction);
        }

        $this->updateTagsGroupes();

        $compteMasterOrigin = CompteClient::getInstance()->find($this->_id);

        parent::save();

        if ($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR && $new) {
            $societe->addCompte($this);
            $societe->save();
        }

        $this->autoUpdateLdap();

        if($this->isSocieteContact() && SocieteConfiguration::getInstance()->getExtras()) {
            foreach($this->getSociete()->getContactsObj() as $compte) {
                if($compte->_id == $this->_id) {
                    continue;
                }

                if(self::isSameExtrasComptes($compte, $compteMasterOrigin)) {
                    $this->pushExtrasEditableToAndSave($compte);
                }
            }
        }
    }

    public static function isSameDroitsComptes(InterfaceCompteGenerique $compte1, InterfaceCompteGenerique $compte2) {
        $droits1 = self::getFlatDroits($compte1);
        $droits2 = self::getFlatDroits($compte2);

        return $droits1 == $droits2 || !$droits1;
    }

    public static function isSameExtrasComptes(InterfaceCompteGenerique $compte1, InterfaceCompteGenerique $compte2) {
        $extra1 = $compte1->getExtrasEditables();
        ksort($extra1);
        $extra2 = $compte2->getExtrasEditables();
        ksort($extra2);

        return $extra1 == $extra2 || !count($extra1);
    }

    public static function getFlatDroits(InterfaceCompteGenerique $compte) {
        if(!$compte->exist('droits')) {
            return null;
        }

        $droits = $compte->droits;
        if($droits instanceof acCouchdbJson) {
            $droits = $droits->toArray(true, false);
        }

        $droits = implode(",", $droits);

        return $droits;
    }

    public function pushExtrasEditableToAndSave($compte) {
        $extraCompte = $compte->getExtrasEditables(true);
        ksort($extraCompte);
        $extraThis = $this->getExtrasEditables(true);
        ksort($extraThis);

        if($extraThis == $extraCompte) {
            return;
        }

        $compte->remove('extras');
        $compte->add('extras', $this->extras);
        $compte->save();
    }

    public function updateExtras() {
        $etablissement = null;
        if($this->isEtablissementContact()) {
            $etablissement = $this->getEtablissement();
        }
        $societe = $this->getSociete();
        foreach(SocieteConfiguration::getInstance()->getExtras() as $extraKey => $extraInfos) {
            $this->add('extras')->add($extraKey);

            if(!isset($extraInfos['auto']) || !$extraInfos['auto']) {
                continue;
            }

            $this->extras->add($extraKey);
            $this->extras->set($extraKey, null);

            $fields = explode("|", $extraInfos['auto']);

            foreach($fields as $field) {
                $entity = explode("->", $field)[0];
                $property = explode("->", $field)[1];
                $method = null;

                if(strpos($property, '(') !== false) {
                    $method = str_replace(array("(",")"), null, $property);
                    $property = null;
                }

                if($entity == "societe" && $property && $societe->exist($property)) {
                    $this->extras->add($extraKey, $societe->get($property));
                }

                if($entity == "societe" && $method) {
                    try {
                        $value = call_user_func(array($societe, $method));
                    } catch(Exception $e) {
                        $value = null;
                    }

                    if(is_array($value)) {
                        sort($value);
                        $value = implode('|', $value);
                    }

                    $this->extras->add($extraKey, $value);
                }

                if($entity == "etablissement" && $etablissement && $property && $etablissement->exist($property)) {
                    $this->extras->add($extraKey, $etablissement->get($property));
                }

                if($entity == "etablissement" && $method) {
                    try {
                        $value = call_user_func(array($etablissement, $method));
                    } catch(Exception $e) {
                        $value = null;
                    }

                    if(is_array($value)) {
                        sort($value);
                        $value = implode('|', $value);
                    }

                    $this->extras->add($extraKey, $value);
                }
            }
        }
    }

    public function isEnAlerte() {
        return ($this->exist('en_alerte') && $this->en_alerte);
    }

    private $maintenance = null;
    public function setMaintenance() {
        $this->maintenance = true;
    }

    protected function doSave() {
        if ($this->maintenance) {
            return;
        }
        $this->add('date_modification', date('Y-m-d'));
    }

    public function getDateModification() {
        if(!$this->exist('date_modification')) {

            return $this->getSociete()->date_modification;
        }

        return $this->_get('date_modification');
    }

    public function isSocieteContact() {
        return ($this->getSociete() && ($this->getSociete()->compte_societe == $this->_id));
    }

    private function removeFournisseursTag() {
        $this->removeTags('automatique', array('Fournisseur', 'MDV', 'PLV'));
    }

    public function getFournisseurs() {
        $societe = $this->getSociete();
        if (!$societe->code_comptable_fournisseur)
            return false;

        $fournisseurs = array('Fournisseur');
        if ($societe->exist('type_fournisseur') && count($societe->type_fournisseur->toArray(true, false))) {
            $fournisseurs = array_merge($fournisseurs, $societe->type_fournisseur->toArray(true, false));
        }
        return $fournisseurs;
    }

    public function isEtablissementContact() {

        return $this->getEtablissement() != null;
    }

    public function getEtablissement() {
        if (!$this->cacheEtablissement) {
            $this->cacheEtablissement = $this->getEtablissementReal();
        }
        return $this->cacheEtablissement;

    }


    public function getEtablissementReal() {
        if($this->isSocieteContact()) {
            $societe = $this->getSociete();

            foreach($societe->getEtablissementsObj() as $etablissement) {
                if($etablissement->etablissement->isSameCompteThanSociete()) {

                    return $etablissement->etablissement;
                }
            }

            return null;
        }

        if (!$this->getEtablissementOrigine()) {

            return null;
        }

        return EtablissementClient::getInstance()->find($this->getEtablissementOrigine());
    }

    public function getEtablissementOrigine() {
        foreach ($this->origines as $origine) {
            if (preg_match('/^ETABLISSEMENT/', $origine)) {
                return $origine;
            }
        }
        return null;
    }

    public function getEtablissementOrigineObject() {
        $id = $this->getEtablissementOrigine();
        if(!$id) {

            return null;
        }

        return EtablissementClient::getInstance()->find($id);
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
        if($this->getStatut() == CompteClient::STATUT_SUSPENDU) {
            return CompteClient::STATUT_TELEDECLARANT_INACTIF;
        }

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

    public function getStatutLibelle(){
      return CompteClient::$statutsLibelles[$this->getStatut()];
    }

    /**
     *
     * @param string $mot_de_passe
     */

    public function getLogin() {

        if($this->exist('login')) {
            return $this->_get('login');
        }

        if (SocieteConfiguration::getInstance()->isIdentifiantEtablissementSaisi()) {

            if($this->mot_de_passe) {

                return $this->identifiant;
            }

            if(!$this->mot_de_passe && !$this->getSociete()->getContact()->mot_de_passe) {
                return null;
            }

            if($this->isSocieteContact()) {

                return $this->identifiant;
            }

            if($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {

                return $this->identifiant;
            }

            return $this->getSociete()->getMasterCompte()->login;
        }

        if($this->isSocieteContact()) {
            return $this->getSociete()->identifiant;
        }

        if($this->compte_type == CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
            return $this->identifiant;
        }

        return preg_replace('/01$/', '', $this->identifiant);
    }

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
        if(($this->_id != $this->getSociete()->getMasterCompte()->_id) && ($this->identifiant == $this->getSociete()->getMasterCompte()->login)) {
            if ($verbose) {
                echo "identifiant ".$this->identifiant." identifique à celui de la société\n";
            }
            return;
        }

        $ldap = new CompteLdap();

        if ($this->isActif()) {
            if (!$this->exist('mot_de_passe') || !$this->mot_de_passe) {
                if ($verbose) {
                    echo "No password: no ldap\n";
                }
                return ;
            }
	        try {
                $ldap->saveCompte($this, $verbose);
	        } catch(Exception $e) {
            	echo $this->_id." save ldap : ".$e->getMessage()."\n";
            }

            if (sfConfig::get('app_ldap_autogroup', false)) {
	            $groupldap = new CompteGroupLdap();

                $comptes = $this->getSociete()->getInterlocuteursWithOrdre();

                $groupes = [];

                foreach ($comptes as $compte_id => $info) {
                    $compte = CompteClient::getInstance()->find($compte_id);

                    if ($compte === null ||
                        $compte->compte_type === CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
                        continue;
                    }

                    foreach ($compte->tags as $type => $tags) {
                        if (! array_key_exists($type, $groupes)) {
                            $groupes[$type] = [];
                        }

                        foreach ($tags as $tag) {
                            $short_tag = str_replace(CompteGroupLdap::$blacklist, '', $tag);

                            if (! in_array($short_tag, $groupes[$type])) {
                                $groupes[$type][] = $short_tag;
                            }
                        }
                    }
                }

                $ldapUid = $this->login;

                foreach ($groupldap->getMembership($ldapUid) as $groupe) {
                    $groupldap->removeMember($groupe, $ldapUid);
                }

                $groupldap->saveMultipleGroup($groupes, $ldapUid);

                if ($this->compte_type === CompteClient::TYPE_COMPTE_INTERLOCUTEUR) {
                    foreach ($this->tags as $type => $tags) {
                        if (! array_key_exists($type, $groupes)) {
                            $groupes[$type] = [];
                        }

                        foreach ($tags as $tag) {
                            $short_tag = str_replace(CompteGroupLdap::$blacklist, '', $tag);

                            if (! in_array($short_tag, $groupes[$type])) {
                                $groupes[$type][] = $short_tag;
                            }
                        }
                    }
                    $groupldap->saveMultipleGroup($groupes, $ldapUid);
                }
            }
        } else {
            CompteClient::getInstance()->deleteLdapCompte($this->login);
        }
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

        if ($type_societe == SocieteClient::TYPE_OPERATEUR || $type_societe == SocieteClient::TYPE_COURTIER) {
            $acces_teledeclaration = true;
            $droits->add(Roles::TELEDECLARATION_VRAC, Roles::TELEDECLARATION_VRAC);
            if ($this->getSociete()->isNegociant() || $type_societe == SocieteClient::TYPE_COURTIER) {
                $droits->add(Roles::TELEDECLARATION_VRAC_CREATION, Roles::TELEDECLARATION_VRAC_CREATION);
            }
        }
        if ($type_societe == SocieteClient::TYPE_OPERATEUR && $this->getSociete()->isViticulteur()){
            $acces_teledeclaration = true;
            $droits->add(Roles::TELEDECLARATION_DRM, Roles::TELEDECLARATION_DRM);
        }

        if ($acces_teledeclaration) {
            $droits->add(Roles::TELEDECLARATION, Roles::TELEDECLARATION);

        }
    }

    public function hasDroit($droit) {
        $droits = $this->droits;
        if($droits instanceof acCouchdbJson) {
            $droits = $droits->toArray(true, false);
        }
        if (!is_array($droits)) {
            print_r(['droits' => $droits]);
        }
        foreach($droits as $key => $d) {
            $droitTab = explode(":", $d);
            $droits[$key] = $droitTab[0];
        }

        return in_array($droit, $droits);
    }


    public function getDroitValue($droit) {
        foreach($this->droits as $d) {
            $droitTab = explode(":", $d);
            if($droit != $droitTab[0]) {
                continue;
            }
            return isset($droitTab[1]) ? $droitTab[1] : null;
        }

        return null;
    }

    public function getDroits() {
        if (!$this->exist('droits') && $this->isSocieteContact()) {
            return array();
        }

        if (!$this->exist('droits')) {
            return $this->getSociete()->getMasterCompte()->getDroits();
        }

        return $this->_get('droits');
    }

    public function isInscrit() {

        return $this->getStatutTeledeclarant() != CompteClient::STATUT_TELEDECLARANT_NOUVEAU && $this->getStatutTeledeclarant() != CompteClient::STATUT_TELEDECLARANT_INACTIF;
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
        return Anonymization::hideIfNeeded($this->_get('adresse'));
    }

    public function getAdresseComplementaire() {
        return Anonymization::hideIfNeeded($this->_get('adresse_complementaire'));
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
        return Anonymization::hideIfNeeded($this->_get('email'));
    }
    public function getEmails(){
        return explode(';',$this->email);
    }

    public function getTelephoneDisponible() {
        if ($this->getTelephoneBureau()) {
            return $this->getTelephoneBureau();
        } elseif ($this->getTelephoneMobile()) {
            return $this->getTelephoneMobile();
        } elseif ($this->getTelephonePerso()) {
            return $this->getTelephonePerso();
        } else {
            return null;
        }
    }

    public function getTelephoneBureau() {
        return Anonymization::hideIfNeeded($this->_get('telephone_bureau'));
    }

    public function getTelephonePerso() {
        return Anonymization::hideIfNeeded($this->_get('telephone_perso'));
    }

    public function getTelephoneMobile() {
        return Anonymization::hideIfNeeded($this->_get('telephone_mobile'));
    }

    public function getFax() {
        return Anonymization::hideIfNeeded($this->_get('fax'));
    }

    public function getDistances($lat1, $lon1, $lat2, $lon2)
    {
		 $rlo1 = deg2rad($lon1);
	  	$rla1 = deg2rad($lat1);
	  	$rlo2 = deg2rad($lon2);
	  	$rla2 = deg2rad($lat2);
	 	 $dlo = ($rlo2 - $rlo1) / 2;
	 	  $dla = ($rla2 - $rla1) / 2;
    	$a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
    	$d = 2 * atan2(sqrt($a), sqrt(1 - $a));
    	return (6378137 * $d);
    }

    public function calculCoordonnees() {
      return CompteClient::getInstance()->calculCoordonnees($this->adresse, $this->commune, $this->code_postal);
    }

    public function updateCoordonneesLongLatByNoeud($noeud,$latCompare = false,$lonCompare = false) {

        $coordonnees = CompteClient::getInstance()->calculCoordonnees($noeud->adresse, $noeud->commune, $noeud->code_postal);

        if(!$coordonnees) {
            return false;
        }
        if($latCompare && $lonCompare){
          if(round($this->getDistances($coordonnees["lat"], $coordonnees["lon"],$latCompare,$lonCompare)) > 20000){
            $coordonnees = CompteClient::getInstance()->calculCoordonnees("", $noeud->commune, $noeud->code_postal);
          }
          if(round($this->getDistances($coordonnees["lat"], $coordonnees["lon"],$latCompare,$lonCompare)) > 20000){
            $coordonnees["lon"] = null;
            $coordonnees["lat"] = null;
          }
        }

        $noeud->lon = $coordonnees["lon"];
        $noeud->lat = $coordonnees["lat"];
        if ($noeud->exist('insee') && !$noeud->get('insee')) {
            $noeud->set('insee', $coordonnees["insee"]);
        }
        return true;
    }

    public function updateCoordonneesLongLat($etbSave = false) {
        $this->updateCoordonneesLongLatByNoeud($this);
        if(!$etb = $this->getEtablissement()){
          return true;
        }
        if(!$etb->exist('chais')) {
            return true;
        }
        foreach($etb->chais as $chai) {
            if($chai->adresse == $this->adresse && $chai->commune == $this->commune) {
                $chai->lon = $this->lon;
                $chai->lat = $this->lat;
                continue;
            }

            $this->updateCoordonneesLongLatByNoeud($chai,$this->lat,$this->lon);

        }
        if($etbSave){
          $etb->save();
        }
        return true;
    }

    public function getExtrasEditables($empty = false) {
        $extras = array();
        foreach(SocieteConfiguration::getInstance()->getExtras() as $k => $e) {
            if ($this->exist('extras') && $this->extras->exist($k)) {
                $e['value'] = $this->extras->get($k);
            }
            if (!isset($e['auto']) || !$e['auto']) {
                if ( $empty || isset($e['value']) ) {
                    $extras[$k] = $e;
                }
            }
        }
        return $extras;
    }

    public function getCoordonneesLatLon() {
        $points = array();
        if($this->lat && $this->lon) {
            $points[$this->lat.$this->lon] = array($this->lat, $this->lon);
        }
        if(!$this->exist('chais')) {

            return $points;
        }
        foreach($this->chais as $chai) {
            if(!$chai->lat && !$chai->lon) {
                continue;
            }
            $points[$chai->lat.$chai->lon] = array($chai->lat, $chai->lon);
        }
        return $points;
    }

    public function hasLatLonChais(){
      $latLong = true;
      if(!$this->getEtablissement() || !$this->getEtablissement()->exist('chais')) {
          return true;
      }
      foreach($this->getEtablissement()->chais as $chai) {
          if ($chai->commune) {
            if(!$chai->lon && !$chai->lat) {
              return false;
            }
          }
      }
      return true;
    }

    public function getNomAAfficher(){
      return Anonymization::hideIfNeeded($this->_get('nom_a_afficher'));
    }

    public function getIdentifiantAAfficher(){
      return $this->getIdentifiant();
    }

    public function getRegion() {
        if (!$this->exist('region')) {
            return null;
        }
        return $this->_get('region');
    }

    public function getRegionViticole(){
      return strtoupper(sfContext::getInstance()->getConfiguration()->getApplication());
    }

    public function getCodeComptable(){
      return ($this->getSociete())? $this->getSociete()->getCodeComptable() : null;
    }

    public function getTagsDegustateur()
    {
        $tags = [];

        if ($this->tags->exist('manuel')) {
            foreach ($this->tags->manuel as $tag) {
                if (strpos($tag, 'degustateur_') === 0) {
                    $tags[] = ucfirst(str_replace('_', ' ', substr($tag, strlen('degustateur_'))));
                }
            }
        }

        return $tags;
    }

    public function getEmailTeledeclaration() {

        return $this->getTeledeclarationEmail();
    }

    public function getTeledeclarationEmail() {
        $email = '';
        if ($this->isEtablissementContact()) {
            $email = $this->getEtablissement()->getTeledeclarationEmail();
        }elseif ($this->isSocieteContact()) {
            $email = $this->getSociete()->getTeledeclarationEmail();
        }
        if ($email && ! in_array($email, $this->getEmails())) {
            return $email;
        }
        return null;
    }


    public function setEmailTeledeclaration($email_teledeclaration) {
        $this->add('teledeclaration_email', $email);
    }


    public function hasAlternativeLogins() {
        if (!$this->exist('alternative_logins')) {
            return false;
        }
        $a = $this->_get('alternative_logins');
        if (count($a) == 1 && !$a[0]) {
            return false;
        }
        return (count($a));
    }

    public function generateCodeCreation() {
        $this->mot_de_passe = CompteClient::getInstance()->generateCodeCreation();
    }

}
