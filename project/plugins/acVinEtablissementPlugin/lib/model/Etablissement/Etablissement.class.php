<?php

class Etablissement extends BaseEtablissement {

    protected $_interpro = null;
    protected $droit = null;
    
    protected $cedex = null;
    protected $adresse_complementaire = null;
    protected $telephone_mobile = null;
    protected $telephone_perso = null;

    /**
     * @return _Compte
     */
    public function getInterproObject() {
        if (is_null($this->_interpro)) {
            $this->_interpro = InterproClient::getInstance()->find($this->interpro);
        }

        return $this->_interpro;
    }

    public function constructId() {
        $this->set('_id', 'ETABLISSEMENT-' . $this->identifiant);
        if ($this->isViticulteur()) {
            $this->raisins_mouts = is_null($this->raisins_mouts) ? EtablissementClient::RAISINS_MOUTS_NON : $this->raisins_mouts;
            $this->exclusion_drm = is_null($this->exclusion_drm) ? EtablissementClient::EXCLUSION_DRM_NON : $this->exclusion_drm;
            $this->type_dr = is_null($this->type_dr) ? EtablissementClient::TYPE_DR_DRM : $this->type_dr;
        }

        if ($this->isViticulteur() || $this->isNegociant()) {
            $this->relance_ds = is_null($this->relance_ds) ? EtablissementClient::RELANCE_DS_OUI : $this->relance_ds;
        }

        $this->statut = is_null($this->statut) ? EtablissementClient::STATUT_ACTIF : $this->statut;
    }

    public function setRelanceDS($value) {
        if (!($this->isViticulteur() || $this->isNegociant())) {
            throw new sfException("Le champs 'relance_ds' n'est valable que pour les viticulteurs ou les négociants");
        }

        $this->_set('relance_ds', $value);
    }

    public function setExclusionDRM($value) {
        if (!($this->isViticulteur())) {
            throw new sfException("Le champs 'exclusion_drm' n'est valable que pour les viticulteurs");
        }

        $this->_set('exclusion_drm', $value);
    }

    public function setRaisinsMouts($value) {
        if (!($this->isViticulteur())) {
            throw new sfException("Le champs 'raisins_mouts' n'est valable que pour les viticulteurs");
        }

        $this->_set('raisins_mouts', $value);
    }

    public function setTypeDR($value) {
        if (!($this->isViticulteur())) {
            throw new sfException("Le champs 'type_dr' n'est valable que pour les viticulteurs");
        }

        $this->_set('type_dr', $value);
    }

    public function getAllDRM() {
        return acCouchdbManager::getClient()->startkey(array($this->identifiant, null))
                        ->endkey(array($this->identifiant, null))
                        ->getView("drm", "all");
    }

    private function cleanPhone($phone) {

        return $phone;
    }

    public function getMasterCompte() {
        if ($this->compte) {
            return CompteClient::getInstance()->find($this->compte);
        }
        return CompteClient::getInstance()->find($this->getSociete()->compte_societe);
    }

    public function getContact() {

        return $this->getMasterCompte();
    }

    public function getSociete() {
        return SocieteClient::getInstance()->find($this->id_societe);
    }

    public function isSameAdresseThanSociete() {
        $comptesociete = $this->getSociete()->getContact();
        return (($comptesociete->adresse == $this->siege->adresse)  || ! $this->siege->adresse )&&
                (($comptesociete->commune == $this->siege->commune) || ! $this->siege->commune) &&
                (($comptesociete->code_postal == $this->siege->code_postal) ||  !$this->siege->code_postal) &&
                (($comptesociete->cedex == $this->cedex) || !$this->cedex) &&
                (($comptesociete->adresse_complementaire == $this->adresse_complementaire) || !$this->adresse_complementaire)&&
                (($comptesociete->pays == $this->siege->pays) || !$this->siege->pays);
    }

    public function isSameContactThanSociete() {
        $comptesociete = $this->getSociete()->getContact();
        return (($comptesociete->telephone_bureau === $this->telephone) || !$this->telephone) &&
            (($comptesociete->telephone_mobile === $this->telephone_mobile) || !$this->telephone_mobile ) &&
            (($comptesociete->telephone_perso === $this->telephone_perso) || !$this->telephone_perso) &&
            (($comptesociete->email === $this->email) || !$this->email) &&
            (($comptesociete->fax === $this->fax) || !$this->fax) ;
    }

    public function isSameCompteThanSociete() {
        return ($this->compte == $this->getSociete()->compte_societe);
    }

    public function getNumCompteEtablissement() {
        if (!$this->compte)
            return null;
        if ($this->compte != $this->getSociete()->compte_societe)
            return $this->compte;
        return null;
    }

    public function getNoTvaIntraCommunautaire() {
        $societe = $this->getSociete();

        if (!$societe) {

            return null;
        }

        return $societe->no_tva_intracommunautaire;
    }

    public function setAdresse($s) {
        return ($this->siege->adresse = $s);
    }

    public function setCommune($s) {
        return ($this->siege->commune = $s);
    }

    public function setCodePostal($s) {
        return ($this->siege->code_postal= $s);
    }

    public function setPays($s) {
        return ($this->siege->pays = $s);
    }

    public function setCedex($s) {
        $this->cedex = $s;
        return true;
    }
    public function setAdresseComplementaire($s) {
        $this->adresse_complementaire = $s;
        return true;
    }

    public function getCedex() {
        if (!$this->cedex) {
            $this->cedex = $this->getMasterCompte()->cedex;
        }
        return $this->cedex;
    }
    public function getAdresseComplementaire() {
        if (!$this->adresse_complementaire) {
            $this->adresse_complementaire = $this->getMasterCompte()->adresse_complementaire;
        }
        return $this->adresse_complementaire;
    }

    public function setTelephonePerso($s) {
        $this->telephone_perso = $s;
        return true;
    }
    
    public function setTelephoneMobile($s) {
        $this->telephone_mobile = $s;
        return true;
    }

    public function getTelephonePerso() {
        if (!$this->telephone_perso) {
            $this->telephone_perso = $this->getMasterCompte()->telephone_perso;
        }
        return $this->telephone_perso;
    }

    public function getTelephoneMobile() {
        if (!$this->telephone_mobile) {
            $this->telephone_mobile = $this->getMasterCompte()->telephone_mobile;
        }
        return $this->telephone_mobile;
    }

    public function setFax($fax) {
        if ($fax)
            $this->_set('fax', $this->cleanPhone($fax));
    }

    public function setTelephone($phone, $idcompte = null) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone));
    }

    public function getDenomination() {

        return ($this->nom) ? $this->nom : $this->raison_sociale;
    }

    public function addLiaison($type, $etablissement) {
        if (!in_array($type, EtablissementClient::listTypeLiaisons()))
            throw new sfException("liaison type \"$type\" unknown");
        $liaison = $this->liaisons_operateurs->add($type . '_' . $etablissement->_id);
        $liaison->type_liaison = $type;
        $liaison->id_etablissement = $etablissement->_id;
        $liaison->libelle_etablissement = $etablissement->nom;
        return $liaison;
    }

    public function isNegociant() {
        return ($this->famille == EtablissementFamilles::FAMILLE_NEGOCIANT);
    }

    public function isViticulteur() {
        return ($this->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR);
    }

    public function isCourtier() {
        return ($this->famille == EtablissementFamilles::FAMILLE_COURTIER);
    }

    public function getFamilleType() {
        $familleType = array(EtablissementFamilles::FAMILLE_PRODUCTEUR => 'vendeur',
            EtablissementFamilles::FAMILLE_NEGOCIANT => 'acheteur',
            EtablissementFamilles::FAMILLE_COURTIER => 'mandataire');
        return $familleType[$this->famille];
    }

    public function getDepartement() {
        if ($this->siege->code_postal) {
            return substr($this->siege->code_postal, 0, 2);
        }
        return null;
    }

    public function getDroit() {
        if (is_null($this->droit)) {

            $this->droit = new EtablissementDroit($this);
        }

        return $this->droit;
    }

    public function hasDroit($droit) {

        return $this->getDroit()->has($droit);
    }

    public function getDroits() {
        return EtablissementFamilles::getDroitsByFamilleAndSousFamille($this->famille, $this->sous_famille);
    }

    public function isInterpro() {
        return ($this->region != EtablissementClient::REGION_HORS_CVO);
    }

    protected function initFamille() {
        if (!$this->famille) {
            $this->famille = EtablissementFamilles::FAMILLE_PRODUCTEUR;
        }
        if (!$this->sous_famille) {
            $this->sous_famille = EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE;
        }
    }

    public function save() {
        if(!$this->isSameAdresseThanSociete() || !$this->isSameContactThanSociete()){
            //créer
            if ($this->isSameCompteThanSociete()) {
                $compte = CompteClient::getInstance()->createCompteFromEtablissement($this); 
                $compte->addOrigine($this->_id);
            }else{
                $compte = $this->getMasterCompte();
            }
            $compte->adresse = $this->siege->adresse;
            $compte->commune= $this->siege->commune;
            $compte->code_postal = $this->siege->code_postal;
            $compte->pays = $this->siege->pays;
            $compte->cedex = $this->cedex;
            $compte->telephone_bureau= $this->telephone;
            $compte->email = $this->email;
            $compte->fax = $this->fax;
            $compte->telephone_perso = $this->telephone_perso;
            $compte->telephone_mobile = $this->telephone_mobile;
            $compte->id_societe = $this->getSociete()->_id;
            $compte->save();          
            $this->setCompte($compte->_id);
        }else if(!$this->isSameCompteThanSociete()){
             $compteid = $this->getCompte();
             $mcompte = $this->getSociete()->getMasterCompte();
             $this->setCompte($mcompte->_id);
             CompteClient::getInstance()->find($compteid)->delete();
             $this->siege->adresse = $mcompte->adresse;
             $this->siege->commune = $mcompte->commune;
             $this->siege->code_postal = $mcompte->code_postal;
             $this->siege->pays = $mcompte->pays;
             $this->telephone = $mcompte->telephone_bureau;
             $this->email = $mcompte->email;
             $this->fax = $mcompte->fax;
             $this->telephone_perso = $mcompte->telephone_perso;
             $this->telephone_mobile = $mcompte->telephone_mobile;
        }
        
        $this->initFamille();
        parent::save();
    }

    public function isActif() {
        return ($this->statut == EtablissementClient::STATUT_ACTIF);
    }

    public function setIdSociete($id) {
        $soc = SocieteClient::getInstance()->find($id);
        if (!$soc)
            throw new sfException("$id n'est pas une société connue");
        $this->_set("id_societe", $id);
    }

    public function __toString() {

        return sprintf('%s (%s)', $this->nom, $this->identifiant);
    }

    public function getBailleurs() {
        $bailleurs = array();
        if (!(count($this->liaisons_operateurs)))
            return $bailleurs;
        $liaisons = $this->liaisons_operateurs;
        foreach ($liaisons as $key => $liaison) {
            if ($liaison->type_liaison == EtablissementClient::TYPE_LIAISON_BAILLEUR)
                $bailleurs[$key] = $liaison;
        }
        return $bailleurs;
    }

    public function findBailleurByNom($nom) {
        $bailleurs = $this->getBailleurs();
        foreach ($bailleurs as $key => $liaison) {
            if ($liaison->libelle_etablissement == str_replace("&", "", $nom))
                return EtablissementClient::getInstance()->find($liaison->id_etablissement);
            if ($liaison->exist('aliases'))
                foreach ($liaison->aliases as $alias) {
                    if (strtoupper($alias) == strtoupper(str_replace("&", "", $nom)))
                        return EtablissementClient::getInstance()->find($liaison->id_etablissement);
                }
        }
        return null;
    }

    public function addAliasForBailleur($identifiant_bailleur, $alias) {
        $bailleurNameNode = EtablissementClient::TYPE_LIAISON_BAILLEUR . '_' . $identifiant_bailleur;
        if (!$this->liaisons_operateurs->exist($bailleurNameNode))
            throw new sfException("La liaison avec le bailleur $identifiant_bailleur n'existe pas");
        if (!$this->liaisons_operateurs->$bailleurNameNode->exist('aliases'))
            $this->liaisons_operateurs->$bailleurNameNode->add('aliases');
        $this->liaisons_operateurs->$bailleurNameNode->aliases->add(str_replace("&amp;", "", $alias), str_replace("&amp;", "", $alias));
    }

    public function getSiegeAdresses() {
        $a = $this->siege->adresse;
        if ($this->siege->exist("adresse_complementaire")) {
            $a .= ' ; ' . $this->siege->adresse_complementaire;
        }
        return $a;
    }

    public function findEmail() {
        $etablissementPrincipal = $this->getSociete()->getEtablissementPrincipal();
        if ($this->_get('email')) {
            return $this->get('email');
        }
        if (($etablissementPrincipal->identifiant == $this->identifiant) || !$etablissementPrincipal->exist('email') || !$etablissementPrincipal->email) {
            return false;
        }
        return $etablissementPrincipal->get('email');
    }

    public function getEtablissementPrincipal() {
        return SocieteClient::getInstance()->find($this->id_societe)->getEtablissementPrincipal();
    }

    public function hasCompteTeledeclarationActivate() {
        return $this->getSociete()->getMasterCompte()->isTeledeclarationActive();
    }

    public function getEmailTeledeclaration() {
        if ($this->exist('teledeclaration_email') && $this->teledeclaration_email) {
            return $this->teledeclaration_email;
        }
        if ($this->exist('email') && $this->email) {
            return $this->email;
        }
        return null;
    }

    public function setEmailTeledeclaration($email) {
        $this->add('teledeclaration_email', $email);
    }

    public function hasRegimeCrd() {
        return $this->exist('crd_regime') && $this->crd_regime;
    }

    public function getCrdRegime() {

        return EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU;
    }

    public function addCommentaire($s) {
        $c = $this->get('commentaire');
        if ($c) {
            return $this->_set('commentaire', $c . "\n" . $s);
        }
        return $this->_set('commentaire', $s);
    }

}
