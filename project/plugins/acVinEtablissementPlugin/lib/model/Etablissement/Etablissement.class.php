<?php

class Etablissement extends BaseEtablissement implements InterfaceCompteGenerique {

    protected $_interpro = null;
    protected $droit = null;
    protected $societe = null;

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

    public function setCompte($c) {
      return $this->_set('compte', $c);
    }

    public function getMasterCompte() {
      if ($this->compte) {
          $c = CompteClient::getInstance()->find($this->compte);
          if($c){
            return $c;
          }
          return $this->getSociete()->getCompte($this->compte);
      }
      return $this->getSociete()->getCompte($this->getSociete()->compte_societe);
    }

    public function getContact() {

        return $this->getMasterCompte();
    }

    public function getSociete() {
      if (!$this->societe) {
          $this->societe = SocieteClient::getInstance()->findSingleton($this->id_societe);
      }
      return $this->societe;
    }

    public function setSociete($s) {
      $this->societe = $s;
    }

    public function isSameAdresseThanSociete() {
        if (!$this->getSociete()->getMasterCompte()) {
            return false;
        }
        return $this->isSameAdresseThan($this->getSociete()->getMasterCompte());
    }

    public function isSameContactThanSociete() {
        if (!$this->getSociete()->getMasterCompte()) {
            return false;
        }
        return $this->isSameContactThan($this->getSociete()->getMasterCompte());
    }

    public function isSameDroitsThanSociete() {
        return Compte::isSameDroitsComptes($this->getMasterCompte(), $this->getSociete()->getMasterCompte());
    }

    public function isSameExtrasThanSociete() {
        return Compte::isSameExtrasComptes($this->getMasterCompte(), $this->getSociete()->getMasterCompte());
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

    public function getDenomination() {

        return ($this->nom) ? $this->nom : $this->raison_sociale;
    }

    public function existLiaison($type, $etablissementId) {

        return $this->liaisons_operateurs->exist($type . '_' . $etablissementId);
    }

    public function getLiaisonByTypeAndEtablissementId($type, $etablissementId) {
        return $this->liaisons_operateurs->get($type . '_' . $etablissementId);
    }

    public function addLiaison($type, $etablissement,$saveOther = true, $chai = null, $attributsChai = array()) {

        if(!$etablissement instanceof Etablissement) {
            $etablissement = EtablissementClient::getInstance()->find($etablissement);
        }

        if (!in_array($type, array_keys(EtablissementClient::getTypesLiaisons()))) {
            throw new sfException("liaison type \"$type\" unknown");
        }

        $liaison = $this->liaisons_operateurs->add($type . '_' . $etablissement->_id);

        $liaison->type_liaison = $type;
        $liaison->id_etablissement = $etablissement->_id;
        $liaison->libelle_etablissement = $etablissement->nom;

        $libellesTypeRelation = EtablissementClient::getTypesLiaisons();
        $compte = $this->getMasterCompte();
        $compte->addTag('relations',$libellesTypeRelation[$type]);
        if ($this->maintenance) {
            $compte->setMaintenance();
        }
        $compte->save();

        if($etablissement->exist('ppm') && $etablissement->ppm){
          $liaison->ppm = $etablissement->ppm;
        }
        if($etablissement->exist('cvi') && $etablissement->cvi){
          $liaison->cvi = $etablissement->cvi;
        }

        if(EtablissementClient::isTypeLiaisonCanHaveChai($liaison->type_liaison) && $chai) {
            $liaison->hash_chai = $chai->getHash();
            $liaison->add("attributs_chai", $attributsChai);
        }

        if ($saveOther) {
            $this->updateLiaisonOpposee($liaison);
        }

        return $liaison;
    }

    protected function updateLiaisonOpposee($liaison) {
        $etablissement = $liaison->getEtablissement();
        if ($this->maintenance) {
            $etablissement->setMaintenance();
        }

        $typeLiaisonOpposee = EtablissementClient::getTypeLiaisonOpposee($liaison->type_liaison);

        if ($this->isSuspendu()) {
            if ($etablissement->existLiaison($typeLiaisonOpposee, $this->_id)) {
                $etablissement->removeLiaison($etablissement->getLiaisonByTypeAndEtablissementId($typeLiaisonOpposee, $this->_id)->getkey(), false);
                $etablissement->save();
            }
            return;
        }
        if ($etablissement->existLiaison($typeLiaisonOpposee, $this->_id)) {
            return ;
        }

        $chaiOppose = null;
        $attributsChaiOpposes = array();

        if(EtablissementClient::isTypeLiaisonCanHaveChai($typeLiaisonOpposee) && $liaison->getChai()) {
            $chaiOppose = $liaison->getChai();
            $attributsChaiOpposes = $attributsChai;
        }

        if($typeLiaisonOpposee) {
            $etablissement->addLiaison($typeLiaisonOpposee, $this, false, $chaiOppose, $attributsChaiOpposes);
            $etablissement->save();
        }
    }

    public function updateLiaisonsOpposees() {
        foreach($this->liaisons_operateurs as $k => $l) {
            $this->updateLiaisonOpposee($l);
        }
    }

    public function removeLiaison($key, $removeOther = true) {
        if(!$this->liaisons_operateurs->exist($key)) {

            return;
        }

        $liaison = $this->liaisons_operateurs->get($key);

        $typeLiaisonOpposee = EtablissementClient::getTypeLiaisonOpposee($liaison->type_liaison);

        if($removeOther && $typeLiaisonOpposee) {
            $etablissement = $liaison->getEtablissement();
            if ($this->maintenance) {
                $etablissement->setMaintenance();
            }
            $etablissement->removeLiaison($typeLiaisonOpposee."_".$this->_id, false);
            $etablissement->save();
        }

        $compte = $this->getMasterCompte();
        $compte->removeTags('manuel', array($liaison->type_liaison));
        if ($this->maintenance) {
            $compte->setMaintenance();
        }
        $compte->save();
        $this->liaisons_operateurs->remove($key);

    }

    public function hasLiaisonsChai(){
        foreach ($this->liaisons_operateurs as $liaison) {
            if($liaison->getChai()){
                return true;
            }
        }
        return false;
    }

    public function isNegociant() {
        return ($this->famille == EtablissementFamilles::FAMILLE_NEGOCIANT || $this->famille == EtablissementFamilles::FAMILLE_NEGOCIANT_PUR);
    }
    public function isCooperative() {
        return ($this->famille == EtablissementFamilles::FAMILLE_COOPERATIVE);
    }

    public function isViticulteur() {
        return ($this->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR || $this->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR_VINIFICATEUR);
    }

    public function isNegociantVinificateur() {
        return ($this->famille == EtablissementFamilles::FAMILLE_NEGOCIANT_VINIFICATEUR);
    }

    public function isCaveCooperative() {
        return ($this->famille == EtablissementFamilles::FAMILLE_COOPERATIVE);
    }

    public function isCourtier() {
        return ($this->famille == EtablissementFamilles::FAMILLE_COURTIER);
    }
    public function isRepresentant() {
        return ($this->famille == EtablissementFamilles::FAMILLE_REPRESENTANT);
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

    public function getDroits()
    {
        if (class_exists(EtablissementDroits::class)) {
            return EtablissementDroits::getDroits($this);
        }

        throw new sfException("La classe ".EtablissementDroits::class." n'existe pas");
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

    protected function initFamille() {
        if (!$this->famille) {
            $this->famille = EtablissementFamilles::FAMILLE_PRODUCTEUR;
        }
    }

    private $maintenance = null;
    public function setMaintenance() {
        $this->maintenance = true;
    }

    public function save() {
        if(SocieteConfiguration::getInstance()->isDisableSave()) {

            throw new Exception("L'enregistrement des sociétés, des établissements et des comptes sont désactivés");
        }

        $societe = $this->getSociete();

        $compte = $societe->findOrCreateCompteFromEtablissement($this);

        $compte->addOrigine($this->_id);

        $this->pushContactAndAdresseTo($compte);

        $compte->id_societe = $this->getSociete()->_id;
        $compte->nom = $this->nom;
        $compte->statut = $this->statut;
        $compte->commentaire = $this->commentaire;

        $this->compte = $compte->_id;


        if($this->isSameAdresseThanSociete()) {
            $this->pullAdresseFrom($this->getSociete()->getMasterCompte());
        }
        if($this->isSameContactThanSociete()) {
            $this->pullContactFrom($this->getSociete()->getMasterCompte());
        }
        $this->initFamille();
        $this->raison_sociale = $societe->raison_sociale;
        $this->siret = $societe->siret;
        $this->interpro = "INTERPRO-declaration";
        if(class_exists("VracConfiguration") && VracConfiguration::getInstance()->getRegionDepartement() !== false) {
            $this->region = EtablissementClient::getInstance()->calculRegion($this);
        }

        $needSocieteSave = false;
        if($this->isNew()) {
          $needSocieteSave = true;
          $societe->addEtablissement($this);
        }
        if($compte->region != $this->region) {
            $needSocieteSave = true;
        }
        $this->updateSiegeAdresse();
        parent::save();

        if ($this->getMasterCompte()) {
            $this->getMasterCompte()->setStatut($this->getStatut());
        }
        $compte->setStatut($this->getStatut());
        if ($this->maintenance) {
            $compte->setMaintenance();
        }
        $compte->save();

        if($needSocieteSave) {
            if ($this->maintenance) {
                $societe->setMaintenance();
            }
            $societe->save();
        }
    }

    public function updateSiegeAdresse() {
        if ($this->adresse != $this->siege->adresse) {
            $this->siege->adresse = $this->adresse;
        }
        if ($this->adresse_complementaire != $this->siege->adresse_complementaire) {
            $this->siege->adresse_complementaire = $this->adresse_complementaire;
        }
        if ($this->code_postal != $this->siege->code_postal) {
            $this->siege->code_postal = $this->code_postal;
        }
        if ($this->commune != $this->siege->commune) {
            $this->siege->commune = $this->commune;
        }
        if ($this->pays != $this->siege->pays) {
            $this->siege->pays = $this->pays;
        }
        if ($this->insee != $this->siege->insee) {
            $this->siege->insee = $this->insee;
        }
    }

    public function delete() {
      $this->getSociete()->removeEtablissement($this);
      parent::delete();
    }

    public function isActif() {
        return $this->statut && ($this->statut == EtablissementClient::STATUT_ACTIF);
    }

     public function isSuspendu() {
        return $this->statut && ($this->statut == SocieteClient::STATUT_SUSPENDU);
    }


    public function setIdSociete($id) {
        $soc = SocieteClient::getInstance()->findSingleton($id);
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
    public function getEmails(){
        return explode(';',$this->email);
    }

    public function getUniqueEmail() {
    	$emails = $this->getEmails();
    	return (isset($emails[0]))? $emails[0] : null;
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
        return SocieteClient::getInstance()->findSingleton($this->id_societe)->getEtablissementPrincipal();
    }

    public function hasCompteTeledeclarationActivate() {
        return $this->getSociete()->getMasterCompte()->isTeledeclarationActive();
    }

    // Deprecated use getTeledeclarationEmail instead
    public function getEmailTeledeclaration() {
        return $this->getTeledeclarationEmail();
    }

    public function getTeledeclarationEmail() {
        if ($this->exist('teledeclaration_email') && $this->_get('teledeclaration_email')) {
            return $this->_get('teledeclaration_email');
        }
    	if ($compteSociete = $this->getMasterCompte()) {
	        if ($compteSociete->exist('societe_information') && $compteSociete->societe_information->exist('email') && $compteSociete->societe_information->email) {
	            return $compteSociete->societe_information->email;
	        }
	        return $compteSociete->email;
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


    public function getCommentaires() {
        $lines = explode("\n", str_replace(' - ', "\n", $this->getCommentaire()));
        $fonc_filter = function($value){return rtrim($value);};
        return array_filter($lines, $fonc_filter);
    }

    public function addCommentaire($s) {
        $c = $this->get('commentaire');
        if ($c) {
            return $this->_set('commentaire', $c . "\n" . $s);
        }
        return $this->_set('commentaire', $s);
    }

    public function getCrdRegimeArray(){
        if(!$this->hasRegimeCrd()){
            return null;
        }
        return explode(",",$this->crd_regime);
    }

    public function hasRegimeCollectifAcquitte(){
        return in_array(EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE, $this->getCrdRegimeArray());
    }
    public function hasRegimeCollectifSuspendu(){
        return in_array(EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU, $this->getCrdRegimeArray());
    }
    public function hasRegimePersonnalise(){
        return in_array(EtablissementClient::REGIME_CRD_PERSONNALISE, $this->getCrdRegimeArray());
    }

    public function getNatureLibelle() {
        if(!$this->exist('nature_inao') || !$this->nature_inao){
            return null;
        }
        return EtablissementClient::getInstance()->getNatureInaoLibelle($this->nature_inao);
    }

    public function hasLegalSignature() {
      return $this->getSociete()->hasLegalSignature();
    }

    public function getMoisToSetStock()
    {
        if ($this->exist('mois_stock_debut') && $this->mois_stock_debut) {
            return $this->mois_stock_debut;
        }
        return DRMPaiement::NUM_MOIS_DEBUT_CAMPAGNE;
    }

    public function hasFamille($famille) {

        return $this->famille == $famille;
    }

    public function getSiret() {
    	if (!$this->_get('siret')) {
    		$this->siret = $this->getSociete()->getSiret();
    	}
    	return Anonymization::hideIfNeeded($this->_get('siret'));
    }

    /**** FONCTIONS A RETIRER APRES LE MERGE ****/


      public function isSameCompteThanSociete() {

        return ($this->compte == $this->getSociete()->compte_societe);
    }

    /**** FIN FONCTIONS A RETIRER APRES LE MERGE ****/

    public function getNumeroCourt() {

        return str_replace(str_replace('SOCIETE-', '', $this->id_societe), '', $this->identifiant);
    }

    public function setStatut($s) {
        $r = $this->_set('statut', $s);
        $this->updateLiaisonsOpposees();
        return $r;
    }

    public function getStatutLibelle(){
      return CompteClient::$statutsLibelles[$this->getStatut()];
    }

    public function getRaisonSociale() {
        return Anonymization::hideIfNeeded($this->_get('raison_sociale'));
    }

    public function getNom() {
        return Anonymization::hideIfNeeded($this->_get('nom'));
    }

    public function getAdresse() {
        return Anonymization::hideIfNeeded($this->_get('adresse'));
    }
    public function getAdresseComplementaire() {
        return Anonymization::hideIfNeeded($this->_get('adresse_complementaire'));
    }

    public function getMeAndLiaisonOfType($type) {
        return array_merge(array($this), $this->getLiaisonObjectOfType($type));
    }

    public function  getLiaisonObjectOfType($type) {
        $etablissements = array();
        foreach ($this->getLiaisonOfType($type) as $o) {
            $e = EtablissementClient::getInstance()->find($o->id_etablissement);
            if ($e && ($e->cvi || $e->ppm)) {
                $etablissements[] = $e;
            }
        }
        return $etablissements;
    }

    public function  getLiaisonOfType($type) {
        $liaisons = array();
        if ($this->exist('liaisons_operateurs')) {
            foreach ($this->liaisons_operateurs as $k => $o) {
                if ($o->type_liaison == $type) {
                    $liaisons[] = $o;
                }
            }
        }
        return $liaisons;
    }

    public function getLaboLibelle() {
        $labos = $this->getLiaisonOfType(EtablissementClient::TYPE_LIAISON_LABO);
        if (!count($labos)) {
            return null;
        }
        return $labos[0]->libelle_etablissement;
    }

    public function getLiaisonsOperateursSorted() {
        $liaisonsOperateurs = $this->liaisons_operateurs->toArray();

        uasort($liaisonsOperateurs, function($a, $b) {
            return $a->libelle_etablissement > $b->libelle_etablissement;
        });

        return $liaisonsOperateurs;
    }

    public function hasLieuxStockage() {

        return $this->exist('lieux_stockage') && count($this->lieux_stockage) > 0;
    }

    public function removeLieuxStockage($identifiant = null) {
        $lieuxToRemove = array();
        if(!$this->exist('lieux_stockage')) {
            return;
        }
        foreach($this->_get('lieux_stockage') as $lieuStockage) {
            if($identifiant && !preg_match("/".$identifiant."/", $lieuStockage->getKey())) {
                continue;
            }
            $lieuxToRemove[$lieuStockage->getKey()] = $lieuStockage->getKey();
        }

        foreach($lieuxToRemove as $key) {
            $this->add('lieux_stockage')->remove($key);
        }
    }

    public function getLieuxStockage($ajoutLieuxStockage = false, $identifiant = null)
    {
        if($ajoutLieuxStockage && $this->isAjoutLieuxDeStockage() &&
                (!$this->exist('lieux_stockage') || (!count($this->getLieuxStockage(false, $identifiant))))){
            $lieu_stockage = $this->storeLieuStockage($this->adresse,
                                                    $this->commune,
                                                   $this->code_postal);
            $this->lieux_stockage->add($lieu_stockage->numero, $lieu_stockage);

            return $this->_get('lieux_stockage');
        }
        if(!$this->exist('lieux_stockage')){

            return array();
        }

        if(is_null($identifiant)) {

            return $this->_get('lieux_stockage');
        }
        $lieuxStockage = array();
        foreach($this->_get('lieux_stockage') as $lieuStockage) {
            if(!preg_match("/".$identifiant."/", $lieuStockage->getKey())) {
                continue;
            }
            $lieuxStockage[$lieuStockage->getKey()] = $lieuStockage;
        }

        return $lieuxStockage;
    }

    public function getLieuStockagePrincipal($ajoutLieuxStockage = false, $identifiant = null) {
        foreach($this->getLieuxStockage($ajoutLieuxStockage, $identifiant) as $lieu_stockage) {

            return $lieu_stockage;
        }

        return null;
    }

    public function isAjoutLieuxDeStockage(){

        return ($this->getFamille() != EtablissementFamilles::FAMILLE_PRODUCTEUR_VINIFICATEUR);
    }

    public function storeLieuStockage($adresse,$commune,$code_postal)
    {
        $newId = 0;
        $identifiant = $this->getIdentifiant();
        if(!$this->exist('lieux_stockage')){
            $this->add('lieux_stockage');
        }
        $lieux_stockage = $this->getLieuxStockage(false, $identifiant);
        foreach ($lieux_stockage as $key => $value) {
            $current_id = intval(str_replace($identifiant, '', $key));
            if($current_id > $newId){
                $newId = $current_id;
            }
        }
        $newId = $identifiant.sprintf('%03d',$newId+1);
        $lieu_stockage = new stdClass();
        $lieu_stockage->numero = $newId;
        $lieu_stockage->nom = $this->nom;
        $lieu_stockage->adresse = $adresse;
        $lieu_stockage->commune = $commune;
        $lieu_stockage->code_postal = $code_postal;
        $this->_get('lieux_stockage')->add($newId, $lieu_stockage);
        return $lieu_stockage;
    }

    public function haveLiaison($etablissement){
        foreach ($this->liaisons_operateurs as $key => $value) {
            if($value->id_etablissement == $etablissement->_id ){
                return true;
            }
        }
        return false;
    }

    public function getEtablissementsLies(){
        $result=array();
        foreach($this->liaisons_operateurs as $id => $tab){
            $result[$tab['id_etablissement']]=$tab['libelle_etablissement'];
        }
        return $result;
    }
}
