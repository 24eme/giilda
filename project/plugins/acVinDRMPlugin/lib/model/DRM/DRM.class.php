<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM implements InterfaceMouvementDocument, InterfaceVersionDocument, InterfaceDeclarantDocument, InterfaceArchivageDocument, InterfaceDroitDocument, InterfaceValidableDocument {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';
    const DETAILS_KEY_SUSPENDU = 'details';
    const DETAILS_KEY_ACQUITTE = 'detailsACQUITTE';

    const CONTROLE_POINT_ENGAGEMENT = 'engagement';
    const CONTROLE_POINT_VIGILANCE = 'vigilance';
    const CONTROLE_POINT_BLOCANT = 'erreur';
    const CONTROLE_TRANSMISSION = 'transmission';
    const CONTROLE_COHERENCE = 'coherence';

    protected $mouvement_document = null;
    protected $version_document = null;
    protected $declarant_document = null;
    protected $archivage_document = null;
    protected $document_precedent = null;
    protected $document_suivant = null;

    public function __construct() {
        parent::__construct();
        $this->initDocuments();
    }

    public function __clone() {
        parent::__clone();
        $this->initDocuments();
        $this->document_precedent = null;
        $this->document_suivant = null;
    }

    protected function initDocuments() {
        $this->mouvement_document = new MouvementDocument($this);
        $this->version_document = new VersionDocument($this);
        $this->declarant_document = new DeclarantDocument($this);
        $this->archivage_document = new ArchivageDocument($this);
    }

    public function loadAllProduits() {
    	$produits = $this->getConfigProduits(true);
    	if (!is_null($produits)) {
    		foreach ($produits as $hash => $produit) {
    			$this->addProduit($hash, DRM::DETAILS_KEY_SUSPENDU);
    		}
    	}
    }

    public function constructId() {

        $this->set('_id', DRMClient::getInstance()->buildId($this->identifiant, $this->periode, $this->version));
    }

    public function getCampagne() {

        return $this->_get('campagne');
    }

    public function getFirstDayOfPeriode() {
        return substr($this->periode, 0, 4) . '-' . substr($this->periode, 4, 2) . '-01';
    }

    public function getPeriodeAndVersion() {

        return DRMClient::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function getMois() {

        return DRMClient::getInstance()->getMois($this->periode);
    }

    public function getAnnee() {

        return DRMClient::getInstance()->getAnnee($this->periode);
    }

    public function getDate() {

        return DRMClient::getInstance()->buildDate($this->periode);
    }

    public function isTeledeclare() {

        return ($this->exist('teledeclare') && $this->teledeclare) || ($this->exist('type_creation') && $this->type_creation == 'IMPORT');
    }

    public function changedToTeledeclare() {
        $drmPrecedente = $this->getPrecedente();
        return $this->isTeledeclare() && $drmPrecedente && !$drmPrecedente->isTeledeclare();
    }

    public function changedImportToCreation() {
        $drmPrecedente = $this->getPrecedente();

        return !$this->isImport() && $drmPrecedente && $drmPrecedente->isImport();
    }

    public function isImport() {
      return ($this->type_creation == 'IMPORT');
    }

    public function setPeriode($periode) {
        $this->campagne = DRMClient::getInstance()->buildCampagne($periode);

        return $this->_set('periode', $periode);
    }

    public function getProduit($hash, $detailsKey, $denomination_complementaire = null, $tav = null) {
        if (!$this->exist($hash)) {

            return false;
        }

        if(!$this->get($hash)->exist($detailsKey)) {

            return false;
        }

        return $this->get($hash)->get($detailsKey)->getProduit($denomination_complementaire, $tav);
    }

    public function addProduit($hash, $detailsKey, $denomination_complementaire = null, $tav = null) {
        if ($p = $this->getProduit($hash, $detailsKey, $denomination_complementaire, $tav)) {
            return $p;
        }
        $detail = $this->getOrAdd($hash)->addDetailsNoeud($detailsKey)->addProduit($denomination_complementaire, $tav);
        $detail->produit_libelle = $detail->getLibelle();

        $this->declaration->reorderByConf();

        return $this->getProduit($hash, $detailsKey, $denomination_complementaire, $tav);
    }

    public function addProduitByInao($inao, $libelleProduit) {
        if(count($this->getConfig()->identifyProductByCodeDouane($inao))) {
            throw new Exception("Le code INAO ".$inao." est présent dans le catalogue produit, il ne peut pas être ajouté via cette méthode");
        }

        $produit = $this->addProduit(DRMConfiguration::getInstance()->getEdiDefaultProduitHash($inao), DRM::DETAILS_KEY_SUSPENDU, $libelleProduit);
        $produit->code_inao = $inao;
        $produit->produit_libelle = $libelleProduit;

        return $produit;
    }

    public function getDepartement() {
        if ($this->declarant->code_postal) {
            return substr($this->declarant->code_postal, 0, 2);
        }

        return null;
    }

    public function getConfig() {

        return ConfigurationClient::getConfiguration($this->getDate());
    }

    public function getConfigProduits($teledeclarationMode = false) {

        return $this->declaration->getConfigProduits($teledeclarationMode);
    }

    public function isDouaneType($douaneType) {
        $keyNeeded = self::DETAILS_KEY_SUSPENDU;

        if($douaneType == DRMClient::TYPE_DRM_ACQUITTE) {
            $keyNeeded = self::DETAILS_KEY_ACQUITTE;
        }

        foreach($this->getProduits() as $produit) {
            if($produit->exist($keyNeeded) && count($produit->get($keyNeeded)) > 0) {

                return true;
            }
        }

        return false;
    }

    public function getConfigProduitsAuto() {

        return $this->declaration->getConfigProduitsAuto();
    }

    public function getProduitsReserveInterpro() {
        $produits = array();
        foreach($this->getProduits() as $p) {
            if ($p->hasRerserveIntepro()) {
                $produits[] = $p;
            }
        }
        return $produits;
    }

    public function getProduits() {
        return $this->declaration->getProduits();
    }

    public function getProduitsDetails($teledeclarationMode = false, $detailsKey = null) {

        return $this->declaration->getProduitsDetails($teledeclarationMode, $detailsKey);
    }

    public function getDetailsAvecCreationVracs(){
      $creationvracs = array();
      foreach ($this->getProduitsDetails($this->teledeclare) as $d) {
          if ($d->sorties->exist('creationvrac_details') && $creationvrac = $d->sorties->creationvrac_details)
              $creationvracs[] = $creationvrac;
          if ($d->sorties->exist('creationvractirebouche_details') && $creationvrac = $d->sorties->creationvractirebouche_details)
              $creationvracs[] = $creationvrac;
      }
      return $creationvracs;
    }

    public function generateByDS(DS $ds) {
        $this->identifiant = $ds->identifiant;
        foreach ($ds->declarations as $produit) {
            $produitConfig = $this->getConfig()->getProduitWithCorrespondanceInverse($produit->hash);
            if (!$produitConfig->isActif()) {

                continue;
            }
            $this->addProduit($produitConfig->produit_hash, DRM::DETAILS_KEY_SUSPENDU);
        }
    }

    public function generateByDRM(DRM $drm) {

        foreach ($drm->getProduitsDetails() as $produit) {
            $produitCepage = $produit->getCepage();
            $produitConfig = $this->getConfig()->getProduitWithCorrespondanceInverse($produitCepage->getHash());
            if (!$produitConfig || !$produitConfig->isActif($this->getDate())) {

                continue;
            }

            $produitTav = ($produit->getTav()) ?: null;
            $p = $this->addProduit($produitConfig->getHash(), $produit->getParent()->getKey(), ($produit->getKey() != 'DEFAUT' && !$produit->denomination_complementaire) ? $produit->produit_libelle : $produit->denomination_complementaire, $produitTav);
            if ($produit->getKey() != 'DEFAUT') {
                $p->produit_libelle = $produit->produit_libelle;
                $p->code_inao = $produit->code_inao;
            }

            if(DRMConfiguration::getInstance()->isRepriseStocksChangementCampagne() && $drm->periode == DRMClient::getInstance()->getPeriodePrecedente($this->periode)) {
                $p->stocks_debut->initial = $produit->total;
                $p->stocks_debut->revendique = $produit->total_revendique;
            }

            if (! $this->isMoisOuvert() && $drm->periode == DRMClient::getInstance()->getPeriodePrecedente($this->periode)) {
                $p->stocks_debut->revendique = $produit->total_revendique;
                $p->stocks_debut->initial = $produit->total;
                $p->produit_libelle = $produit->produit_libelle;
                $p->code_inao = $produit->code_inao;
            }
            if ($produit->getCepage()->hasRerserveIntepro()) {
                $p->getCepage()->add('reserve_interpro', $produit->getCepage()->reserve_interpro);
            }
        }

        foreach($drm->getAllCrds() as $regime => $crds) {
            foreach($crds as $crd) {
                $stock = null;
                if (DRMConfiguration::getInstance()->isRepriseStocksChangementCampagne() && $drm->periode == DRMClient::getInstance()->getPeriodePrecedente($this->periode)) {
                    $stock = $crd->stock_fin;
                }

                if (! $this->isMoisOuvert() && $drm->periode == DRMClient::getInstance()->getPeriodePrecedente($this->periode)) {
                    $stock = $crd->stock_fin;
                }

                $crdNode = $this->getOrAdd('crds')->getOrAdd($regime)->getOrAddCrdNode($crd->genre, $crd->couleur, $crd->centilitrage, $crd->detail_libelle, $stock, true);
                if($crdNode->stock_debut && !$crdNode->stock_fin){
                    $crdNode->stock_fin = $crdNode->stock_debut;
                }
            }
        }

        if (! $this->isMoisOuvert() && $drm->periode == DRMClient::getInstance()->getPeriodePrecedente($this->periode)) {
            $this->precedente = $drm->_id;
            $this->document_precedent = null;
        }

        $this->update();
    }

    public function generateSuivanteByPeriode($periode, $isTeledeclarationMode = false) {

        if (!$isTeledeclarationMode && $this->getHistorique()->hasInProcess()) {

            throw new sfException(sprintf("Une drm est en cours d'édition pour la campagne %s, impossible d'en créer une autre", $this->campagne));
        }

        $is_just_the_next_periode = (DRMClient::getInstance()->getPeriodeSuivante($this->periode) == $periode);
        $keepStock = ($periode > $this->periode);
        $drm_suivante = clone $this;
        $drm_suivante->periode = $periode;
        if($drm_suivante->isMoisOuvert() && !DRMConfiguration::getInstance()->isRepriseStocksChangementCampagne()) {
            $keepStock = false;
        }
        $drm_suivante->teledeclare = $isTeledeclarationMode;
        $drm_suivante->init(array('keepStock' => $keepStock));

        $drm_suivante->update();
        $drm_suivante->storeDeclarant();
        $drm_suivante->etape = ($isTeledeclarationMode) ? DRMClient::ETAPE_CHOIX_PRODUITS : DRMClient::ETAPE_SAISIE;
        if ($is_just_the_next_periode && !$drm_suivante->isMoisOuvert()) {
            $drm_suivante->precedente = $this->_id;
        }


        $tobedeleted = array();
        foreach ($drm_suivante->declaration->getProduitsDetails() as $details) {
            $details->getParent()->createESDetails($details);
            $details->getCepage()->remove('no_movements');
            $details->getCepage()->remove('edited');
            $details->add('no_movements', false);
            $details->add('edited', false);
            if (!$isTeledeclarationMode) {
                if (!$details->getCepage()->getConfig()->isCVOActif($drm_suivante->getDate())) {
                    $tobedeleted[] = $details->getHash();
                }
            }
        }
        foreach ($tobedeleted as $d) {
            $drm_suivante->remove($d);
        }

        $drm_suivante->initProduitsAutres($isTeledeclarationMode);
        $drm_suivante->initCrds();
        if ($drm_suivante->isPaiementAnnualise() && $isTeledeclarationMode) {
            $drm_suivante->initDroitsDouane();
        }
        $drm_suivante->clearAnnexes();

        if (! $this->getEtablissement()->hasRegimeCrd()) {
            $drm_suivante->remove('favoris');
        }

        if (!$drm_suivante->exist('favoris')) {
            $drm_suivante->buildFavoris($this);
        }
        return $drm_suivante;
    }

    public function init($params = array()) {
        parent::init($params);

        $this->remove('_attachments');
        $this->remove('transmission_douane');

        $this->remove('douane');
        $this->add('douane');
        $this->remove('declarant');
        $this->add('declarant');
        $this->remove('editeurs');
        $this->add('editeurs');

        $this->version = null;
        $this->raison_rectificative = null;
        $this->etape = null;
        $this->precedente = null;
        $this->remove('editeurs');
        $this->add('editeurs');
        $this->commentaire = null;

        $this->archivage_document->reset();
        if ($this->declaratif->exist('statistiques')) {
                $this->declaratif->remove('statistiques');
                $this->declaratif->add('statistiques');
        }

        $this->remove('taux_tva');

        $this->devalide();
    }

    public function getTauxTva() {
        if($this->exist('taux_tva')) {

            return $this->_get('taux_tva');
        }

        $this->add('taux_tva', FactureClient::getInstance()->getTauxTva($this->getDate()) / 100);

        return $this->_get('taux_tva');
    }

    protected function addControleMessagesFromPoints($typePoint, $points){
        foreach ($points as $identifiant => $point) {
            $lien = $point->getLien();
            $this->addControleMessage($typePoint, $point->getMessage()." ( $lien )");
        }
    }

    protected function addControleMessage($typePoint, $message){
        $messages = $this->add('controles')->add($typePoint)->add('messages');
        $messages->add(null, $message);
        $this->controles->add($typePoint)->nb = count($messages);
    }

    public function cleanControles(){
        if($this->exist("controles")){
            $this->remove("controles");
        }
    }

    public function cleanTransmission(){
        if($this->exist("transmission_douane")){
            $this->remove('transmission_douane');
        }
    }

    protected function cleanControle($controle){
        if($this->exist("controles")){
            $this->controles->remove($controle);
        }

    }

    /***
     *  @return true if controles changed
     **/
    public function updateControles(){

        $origine_nb_controles = 0;
        if ($this->exist('controles')) {
            foreach($this->controles as $type => $obj) {
                $origine_nb_controles += $obj->nb;
            }
        }

        $this->cleanControles();

        if (!$this->isMaster()) {
            return ($origine_nb_controles);
        }

        $nb_controles = 0;

        if($this->exist("transmission_douane")) {
            //cas d'erreur de transmission
            if ($this->transmission_douane->xml && $this->transmission_douane->success == false ) {
                $this->addControleMessage(DRM::CONTROLE_TRANSMISSION, $this->getTransmissionErreur());
                $nb_controles++;
            }
            //cas d'incoherence
            if ($this->get("transmission_douane")->coherente == false && !is_null($this->get("transmission_douane")->coherente)) {
                $this->addControleMessage(DRM::CONTROLE_COHERENCE, "Non conforme douane");
                $nb_controles++;
            }
        }

        //Points de controles liés à la validation
        if ($this->isValidee()) {
            return ($origine_nb_controles != $nb_controles);
        }
        $points = new DRMValidation($this, true);
        if(!$points->hasPoints()){
            return ($origine_nb_controles != $nb_controles);
        }
        $this->add('controles');
        if($points->hasErreurs()){
            $this->addControleMessagesFromPoints(DRM::CONTROLE_POINT_BLOCANT, $points->getErreurs());
            $nb_controles++;
        }

        if($points->hasVigilances()){
            $this->controles->add(DRM::CONTROLE_POINT_VIGILANCE);
            $this->controles->vigilance->nb = count($points->getVigilances());
            $this->addControleMessagesFromPoints(DRM::CONTROLE_POINT_VIGILANCE, $points->getVigilances());
            $nb_controles++;
        }

        if($points->hasEngagements()){
                $this->controles->add(DRM::CONTROLE_POINT_ENGAGEMENT);
                $this->controles->engagement->nb = count($points->getEngagements());
                $this->addControleMessagesFromPoints(DRM::CONTROLE_POINT_ENGAGEMENT, $points->getEngagements());
                $nb_controles++;
        }
        return ($origine_nb_controles != $nb_controles);
    }

    public function setDroits() {
        $this->remove('droits');
        $this->add('droits');
        foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->genres as $genre) {
                foreach ($genre->appellations as $appellation) {
                    $appellation->updateDroits($this->droits);
                }
            }
        }
    }

    public function getEtablissement() {

        return EtablissementClient::getInstance()->find($this->identifiant);
    }

    public function getInterpro() {
        if ($this->getEtablissement()) {

            return $this->getEtablissement()->getInterproObject();
        }
    }

    public function getHistorique() {

        return DRMClient::getInstance()->getHistorique($this->identifiant, $this->campagne);
    }

    public function setPrecedente($id) {
        $this->document_precedent = null;

        return $this->_set('precedente', $id);
    }

    public function getPrecedente() {
        if (is_null($this->document_precedent) && $this->exist('precedente') && $this->_get('precedente')) {

            $this->document_precedent = DRMClient::getInstance()->find($this->_get('precedente'));
        }

        if (is_null($this->document_precedent)) {

            $this->document_precedent = new DRM();
        }

        return $this->document_precedent;
    }

    public function getSuivante() {
        if (is_null($this->document_suivant)) {
            $periode = DRMClient::getInstance()->getPeriodeSuivante($this->periode);
            $campagne = DRMClient::getInstance()->buildCampagne($periode);
            if ($campagne != $this->campagne) {

                return null;
            }
            $this->document_suivant = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode);

            is_null($this->document_suivant);
            if($this->document_suivant && $this->document_suivant->changedToTeledeclare()) {
                $this->document_suivant = null;
            }

            if($this->document_suivant && $this->document_suivant->changedImportToCreation()) {
                $this->document_suivant = null;
            }
        }

        return $this->document_suivant;
    }

    public function hasSuivante() {
      return ($this->getSuivante());
    }

    public function isSuivanteCoherente() {
        $drm_suivante = $this->getSuivante();

        if (!$drm_suivante) {

            return true;
        }

        if ($this->declaration->total != $drm_suivante->declaration->total_debut_mois) {

            return false;
        }

        if (count($this->getProduitsDetails($this->teledeclare)) > count($drm_suivante->getProduitsDetails($drm_suivante->teledeclare))) {

            return false;
        }

        foreach($this->getProduitsDetails($this->teledeclare) as $detail) {
            if(!$drm_suivante->exist($detail->getHash())) {

                return false;
            }
            $detailSuivante = $drm_suivante->get($detail->getHash());

            foreach($detail->stocks_fin as $keyStock => $valueStock) {
                $keyStockSuivante = str_replace('final', 'initial', $keyStock);
                if(!$detailSuivante->stocks_debut->exist($keyStockSuivante)) {

                    return false;
                }

                if($valueStock != $detailSuivante->stocks_debut->get($keyStockSuivante)) {

                    return false;
                }
            }
        }

        if ($this->droits->douane->getCumul() != $drm_suivante->droits->douane->getCumul()) {

            return false;
        }

        return true;
    }

    public function devalide() {
        $this->etape = null;
        $this->clearMouvements();
        $this->valide->identifiant = '';
        $this->valide->date_saisie = '';
        $this->valide->date_signee = '';
    }

    public function isValidee() {

        return ($this->valide->date_saisie);
    }

    public function cleanDeclaration() {
        $this->cleanDetails();
        $this->cleanCrds();
        $this->cleanAnnexes();
        $this->cleanReserve();
    }

    public function validate($options = null) {
        if ($this->isValidee()) {

            throw new sfException(sprintf("Cette DRM est déjà validée"));
        }
        $this->update();
        $this->storeIdentifiant($options);

        if (!isset($options['validation_step']) || !$options['validation_step']) {
            $this->storeDates();
        }
        $this->cleanDeclaration();

        if (!isset($options['no_droits']) || !$options['no_droits']) {
            //$this->setDroits();
        }

        $this->setInterpros();
        $this->creationVracs();

        $this->generateMouvements();
        if ($this->teledeclare) {
            $this->generateDroitsDouanes();
        }

        if(!isset($options['validation_step']) || !$options['validation_step']) {
            $this->archivage_document->archiver();
            $this->forceModified();
        }

        if (!isset($options['validation_step']) || !$options['validation_step']) {
            if ($this->getSuivante() && $this->isSuivanteCoherente()) {
                $this->getSuivante()->precedente = $this->get('_id');
                $this->getSuivante()->save();
            }
        }
    }

    public function devalidate(){
      $this->valide->date_saisie = null;
      $this->valide->date_signee = null;
      $this->deleteVracs();
      $this->clearMouvements();
    }

    public function storeIdentifiant($options) {
        $identifiant = $this->identifiant;

        if ($options && is_array($options)) {
            if (isset($options['identifiant']))
                $identifiant = $options['identifiant'];
        }

        $this->valide->identifiant = $identifiant;
    }

    public function storeDates() {
        if (!$this->valide->date_saisie) {
            $this->valide->add('date_saisie', date('Y-m-d'));
        }

        if (!$this->valide->date_signee) {
            $this->valide->add('date_signee', date('Y-m-d'));
        }
    }

    public function removeOldVracs(){
        $vracs = array();

        if (!$this->getMouvements()->exist($this->identifiant)) {

            return;
        }

        foreach ($this->getMouvements()->get($this->identifiant) as $cle_mouvement => $mouvement) {
            if (!$mouvement->isVrac()) {
                continue;
            }
            $vrac = $mouvement->getVrac();
            if ($mouvement->type_hash == 'creationvrac_details' && ($vrac->volume_enleve == 0)) {
                $vrac->delete();
            }
        }
    }


    public function updateVracs() {

        $vracs = array();

        if (!$this->getMouvements()->exist($this->identifiant)) {

            return;
        }

        foreach ($this->getMouvements()->get($this->identifiant) as $cle_mouvement => $mouvement) {
            if (!$mouvement->isVrac()) {
                continue;
            }
            $vrac = $mouvement->getVrac();
            if(!$vrac) {
                continue;
            }
            $vrac->updateVolumesEnleves();
            $vracs[$vrac->numero_contrat] = $vrac;
        }

        foreach ($vracs as $vrac) {
            $vrac->save();
        }

        $this->removeOldVracs();
    }

    private function creationVracs() {
        if (!$this->isValidee()) {

            throw new sfException("La DRM doit être validée pour pouvoir créer les contrats vracs à partir des sorties vracs");
        }
        foreach ($this->getDetailsAvecCreationVracs() as $details) {
            foreach ($details as $keyVrac => $vracCreation) {
              $newVrac = $vracCreation->getVrac();
              $newVrac->createVisa();

              $d = (DateTime::createFromFormat('Y-m-d',$newVrac->enlevement_date));
              $enlevement_date = $d->format('c');
              $newVrac->add('date_signature', $enlevement_date);
              $newVrac->date_visa = $d->format('Y-m-d');

              $newVrac->validate();

              $newVrac->valide->add('date_saisie', $enlevement_date);
              $newVrac->save();
            }
        }
    }

    private function deleteVracs() {
        if ($this->isValidee()) {
            throw new sfException("La DRM doit être validée pour pouvoir créer les contrats vracs à partir des sorties vracs");
        }
        foreach ($this->getDetailsAvecCreationVracs() as $details) {
            foreach ($details as $keyVrac => $vracCreation) {
              $newVrac = $vracCreation->getVrac();
              VracClient::getInstance()->delete($newVrac);
            }
        }
    }

    public function setInterpros() {
        $i = $this->getInterpro();
        if ($i)
            $this->interpros->add(0, $i->getKey());
    }

    private function getTotalDroit($type) {
        $total = 0;
        foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
                $total += $appellation->get('total_' . $type);
            }
        }
        return $total;
    }

    private function interpretHash($hash) {
        if (!preg_match('|declaration/certifications/([^/]*)/appellations/([^/]*)/|', $hash, $match)) {

            throw new sfException($hash . " invalid");
        }

        return array('certification' => $match[1], 'appellation' => $match[2]);
    }

    public function setIdentifiant($identifiant) {
      $this->_set('identifiant', $identifiant);
      $this->storeDeclarant();
    }

    private function setDroit($type, $appellation) {
        $configurationDroits = $appellation->getConfig()->getDroitByType($this->getDocument()->getDate(), $type, $this->getInterpro()->get('_id'));
        $droit = $appellation->droits->get($type);
        $droit->ratio = $configurationDroits->ratio;
        $droit->code = $configurationDroits->code;
        $droit->libelle = $configurationDroits->libelle;
    }

    public function isPaiementAnnualise() {
        return $this->societe->exist('paiement_douane_frequence') && $this->societe->paiement_douane_frequence == DRMPaiement::FREQUENCE_ANNUELLE;
    }

    public function getHumanDate() {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        return strftime('%B %Y', strtotime($this->periode . '-01'));
    }

    public function getEuValideDate() {
        return strftime('%d/%m/%Y', strtotime($this->valide->date_signee));
    }

    public function getEuTransmissionDate() {
        if (!$this->hasTransmissionDate()) {
          return  '';
        }
        return strftime('%d/%m/%Y', strtotime($this->transmission_douane->horodatage));
    }

    public function hasTransmissionDate() {
      if (!$this->exist('transmission_douane')) {
        return false;
      }
      if (!$this->get('transmission_douane')->exist('horodatage')) {
        return false;
      }
      return ($this->get('transmission_douane')->get('horodatage'));
    }

    public function isDebutCampagne() {

        return ConfigurationClient::getInstance()->isDebutCampagne($this->periode);
    }

    public function getCurrentEtapeRouting() {
        $etape = sfConfig::get('app_drm_etapes_' . $this->etape);

        return $etape['url'];
    }

    public function setCurrentEtapeRouting($etape) {
        if (!$this->isValidee()) {
            $this->etape = $etape;
            $this->getDocument()->save();
        }
    }

    public function hasApurementPossible() {

        return $this->declaratif->hasApurementPossible();
    }

    public function hasVrac() {
        $detailsVrac = $this->getDetailsAvecVrac();

        return (count($detailsVrac) > 0);
    }

    public function hasConditionneExport() {

        return ($this->declaration->getTotalByKey('sorties/export') > 0);
    }

    public function hasMouvementAuCoursDuMois() {

        return $this->hasVrac() || $this->hasConditionneExport();
    }

    public function isNeant() {
        return $this->exist('type_creation') && ($this->type_creation == DRMClient::DRM_CREATION_NEANT);
    }

    public function isEnvoyee() {
        if (!$this->exist('valide')) {

            return false;
        }

        if (!$this->valide->exist('statut')) {

            return false;
        }

        if ($this->valide->statut != DRMClient::VALIDE_STATUS_VALIDEE_ENVOYEE && $this->valide->statut != DRMClient::VALIDE_STATUS_VALIDEE_RECUE) {

            return false;
        }

        return true;
    }

    public function hasPrecedente() {

        if (!$this->getPrecedente()) {
            return false;
        } elseif ($this->getPrecedente() && $this->getPrecedente()->isNew()) {
            return false;
        } elseif ($this->isMoisOuvert()) {
            return false;
        }

        return true;
    }

    public function hasDetails() {
        return (count($this->declaration->getProduitsDetails($this->teledeclare)) > 0) ? true : false;
    }

    public function hasEditeurs() {
        return (count($this->editeurs) > 0);
    }

    public function getLastEditeur() {
        if ($this->hasEditeurs()) {
            $editeurs = $this->editeurs->toArray();

            return array_pop($editeurs);
        } else {

            return null;
        }
    }

    public function getUser() {
        try {
            return sfContext::getInstance()->getUser();
        } catch (Exception $e) {
            return null;
        }
    }

    public function addEditeur($compte) {
        $editeur = $this->editeurs->add();
        $editeur->compte = $compte->_id;
        $editeur->nom = $compte->nom;
        $editeur->prenom = $compte->prenom;
        $editeur->date_modification = date('Y-m-d');
    }

    protected function preSave() {
        $this->preSaveEditeur();
        $this->archivage_document->preSave();
    }

    public function save() {
        $this->region = $this->getEtablissement()->region;
        $listEntrees = $listSorties = array();
        $key_to_remove = array();
        foreach ($this->getProduitsDetails($this->teledeclare) as $detail) {
            if (!array_key_exists($detail->getConfig()->getHash(), $listEntrees) && !array_key_exists($detail->getConfig()->getHash(), $listSorties)) {
                $listEntrees[$detail->getConfig()->getHash()] = array_keys($detail->getConfig()->getEntreesSorted());
                $listSorties[$detail->getConfig()->getHash()] = array_keys($detail->getConfig()->getSortiesSorted());
            }
            foreach ($detail->entrees as $keyEntree => $valueEntree) {
                if ($valueEntree instanceof DRMESDetails) {
                    continue;
                }
                if ($valueEntree && !in_array($keyEntree, $listEntrees[$detail->getConfig()->getHash()])) {
                    $key_to_remove[] = $detail->getHash().'/entrees/'.$keyEntree;

                }
            }
            foreach ($detail->sorties as $keySortie => $valueSortie) {
                if ($valueSortie instanceof DRMESDetails) {
                    continue;
                }
                if ($valueSortie && !in_array($keySortie, $listSorties[$detail->getConfig()->getHash()])) {
                   $key_to_remove[] = $detail->getHash().'/sorties/'.$keySortie;
                }
            }
        }

        foreach ($key_to_remove as $key) {
           $this->remove($key);
        }
        if (!$this->declarant->famille) {
            $this->declarant->famille = $this->getEtablissement()->famille;
        }
        parent::save();
    }

    protected function doSave() {
        if (!$this->isValidee()) {
            $this->add('date_modification', date('Y-m-d'));
        }else{
            if ($this->exist('editeurs') && $c = count($this->editeurs)) {
                $this->add('date_modification', $this->editeurs[$c - 1 ]->date_modification);
            }else{
                $this->add('date_modification', $this->valide->date_saisie);
            }
        }
        $this->updateControles();
        $drm_precedente = $this->getMother();
        if ($drm_precedente && $drm_precedente->updateControles()) {
            $drm_precedente->save();
        }
        $this->getTauxTva();
    }

    protected function preSaveEditeur() {
        if ($user = $this->getUser()) {
            if ($user->hasCredential(myUser::CREDENTIAL_ADMIN)) {
                $compte = $user->getCompte();
                $canInsertEditeur = true;
                if ($lastEditeur = $this->getLastEditeur()) {
                    $diff = Date::diff($lastEditeur->date_modification, date('Y-m-d'), 'i');
                    if ($diff < 25) {
                        $canInsertEditeur = false;
                    }
                }
                if ($canInsertEditeur) {
                    $this->addEditeur($compte);
                }
            }
        }
    }

    public function __toString() {
        return DRMClient::getInstance()->getLibelleFromId($this->_id);
    }

    public function getHumanPeriode() {

        return ConfigurationClient::getInstance()->getPeriodeLibelle($this->periode);
    }

    public function delete($protect_validee = true) {
        if ($protect_validee && ($this->isValidee() || !$this->isMaster())) {

            throw new sfException("Impossible de supprimer une DRM validée");
        }

        parent::delete();
    }

    /*     * ** VERSION *** */

    public static function buildVersion($rectificative, $modificative) {

        return VersionDocument::buildVersion($rectificative, $modificative);
    }

    public static function buildRectificative($version) {

        return VersionDocument::buildRectificative($version);
    }

    public static function buildModificative($version) {

        return VersionDocument::buildModificative($version);
    }

    public function getVersion() {

        return $this->_get('version');
    }

    public function hasVersion() {

        return $this->version_document->hasVersion();
    }

    public function isVersionnable() {
        if (!$this->isValidee()) {

            return false;
        }

        return $this->version_document->isVersionnable();
    }

    public function getRectificative() {

        return $this->version_document->getRectificative();
    }

    public function isRectificative() {

        return $this->version_document->isRectificative();
    }

    public function isRectifiable() {

        return false;
    }

    public function getModificative() {

        return $this->version_document->getModificative();
    }

    public function isModificative() {

        return $this->version_document->isModificative();
    }

    public function isModifiable() {
        return $this->version_document->isModifiable();
    }

    public function isReouvrable() {

        return $this->isModifiable() && $this->isTeledeclare() && $this->type_creation != 'IMPORT' && $this->isNonFactures() && !$this->hasSuivante();
    }

    public function getPreviousVersion() {

        return $this->version_document->getPreviousVersion();
    }

    public function getMasterVersionOfRectificative() {
        return DRMClient::getInstance()->getMasterVersionOfRectificative($this->identifiant, $this->periode, self::buildVersion($this->getRectificative() - 1, 0));
    }

    public function needNextVersion() {

        return $this->version_document->needNextVersion() || !$this->isSuivanteCoherente();
    }

    public function getMaster() {

        return $this->version_document->getMaster();
    }

    public function isMaster() {

        return $this->version_document->isMaster();
    }

    public function findMaster() {

        return DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);
    }

    public function findDocumentByVersion($version) {

        return DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($this->identifiant, $this->periode, $version));
    }

    public function getMother() {

        return $this->version_document->getMother();
    }

    public function motherGet($hash) {

        return $this->version_document->motherGet($hash);
    }

    public function motherExist($hash) {

        return $this->version_document->motherExist($hash);
    }

    public function motherHasChanged() {
        if ($this->declaration->total != $this->getMother()->declaration->total) {

            return true;
        }

        if (count($this->getProduitsDetails($this->teledeclare)) != count($this->getMother()->getProduitsDetails($this->teledeclare))) {

            return true;
        }

        if ($this->droits->douane->getCumul() != $this->getMother()->droits->douane->getCumul()) {

            return true;
        }

        return false;
    }

    public function getDiffWithMother() {

        return $this->version_document->getDiffWithMother();
    }

    public function isModifiedMother($hash_or_object, $key = null) {

        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function generateRectificative() {

        return $this->version_document->generateRectificative();
    }

    public function generateModificative() {
        $drm_modificatrice = $this->version_document->generateModificative();
        $drm_modificatrice->etape = DRMClient::ETAPE_SAISIE;
        if (!$drm_modificatrice->exist('favoris')) {
            $drm_modificatrice->buildFavoris($this);
        }

        if ($this->exist('transmission_douane') && $this->transmission_douane->success
            && $this->transmission_douane->id_declaration
            && $this->transmission_douane->coherente === true) {

            $drm_modificatrice->add('transmission_douane', $this->transmission_douane);
        }

        return $drm_modificatrice;
    }

    public function generateNextVersion() {

        if (!$this->hasVersion()) {

            return $this->version_document->generateModificativeSuivante();
        }

        return $this->version_document->generateNextVersion();
    }

    public function listenerGenerateVersion($document) {
        if ($this->getHistorique()->hasInProcess()) {

            throw new sfException(sprintf("Une drm est déjà en cours d'édition pour cette campagne %s, impossible d'en créer une autre", $this->campagne));
        }
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {
        $this->replicate($document);
        $document->precedente = $this->get('_id');
        $document->validate();
    }

    protected function replicate($drm) {
        foreach ($this->getDiffWithMother() as $key => $value) {
            if($this->getConfig()->getDocument()->hasDontRevendique()){
                $this->replicateDetail($drm, $key, $value, 'stocks_fin/dont_revendique', 'stocks_debut/dont_revendique');
            }
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/instance', 'stocks_debut/instance');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/final', 'stocks_debut/initial');
        }
    }

    protected function replicateDetail(&$drm, $key, $value, $hash_match, $hash_replication) {
        if (preg_match('|^(/declaration/certifications/.+/appellations/.+/mentions/.+/lieux/.+/couleurs/.+/cepages/.+/details.*/.+)/' . $hash_match . '$|', $key, $match)) {
            $detail = $this->get($match[1]);
            if (!$drm->exist($detail->getHash())) {
            $drm->addProduit($detail->getCepage()->getHash(), $detail->getParent()->getKey(), $detail->denomination_complementaire);
            }
            $drm->get($detail->getHash())->set($hash_replication, $value);
        }
    }

    /*     * ** FIN DE VERSION *** */

    /*     * ** MOUVEMENTS *** */

    public function getMouvements() {

        return $this->_get('mouvements');
    }

    public function getMouvementsCalcule($teledeclaration_drm = false) {
        $mouvements = $this->declaration->getMouvements($teledeclaration_drm);

        if(DRMConfiguration::getInstance()->isMouvementVideNeant() && (!isset($mouvements[$this->getIdentifiant()]) || !count($mouvements[$this->getIdentifiant()]))) {
            $mouvement = DRMMouvement::freeInstance($this->getDocument());
            $mouvement->facture = 0;
            $mouvement->facturable = 0;
            $mouvement->region = $this->getDocument()->region;
            $mouvements[$this->getIdentifiant()][$mouvement->getMD5Key()] = $mouvement;
        }

        return $mouvements;
    }

    public function getMouvementsCalculeByIdentifiant($identifiant, $teledeclaration_drm = false) {

        return $this->mouvement_document->getMouvementsCalculeByIdentifiant($identifiant, $teledeclaration_drm);
    }

    public function generateMouvements() {
        return $this->mouvement_document->generateMouvements($this->teledeclare);
    }

    public function findMouvement($cle, $id = null) {
        return $this->mouvement_document->findMouvement($cle, $id);
    }

    public function facturerMouvements() {

        return $this->mouvement_document->facturerMouvements();
    }

    public function isFactures() {

        return $this->mouvement_document->isFactures();
    }

    public function isNonFactures() {

        return $this->mouvement_document->isNonFactures();
    }

    public function clearMouvements() {
        $this->remove('mouvements');
        $this->add('mouvements');
    }

    /*     * ** FIN DES MOUVEMENTS *** */

    /*     * ** DECLARANT *** */

    public function storeDeclarant() {
        $this->declarant_document->storeDeclarant();
        $this->declarant->getOrAdd('adresse_compta');
        $this->declarant->getOrAdd('caution');
        $this->declarant->getOrAdd('raison_sociale_cautionneur');
        $this->storeSociete();
    }

    public function getEtablissementObject() {
        return $this->getEtablissement();
    }

    public function isFacturable() {
        if($this->isDRMNegociant() && !DRMConfiguration::getInstance()->isNegociantFacturable()) {

            return false;
        }


        return true;
    }

    public function isDRMNegociant() {

        return ($this->getFamille() == EtablissementFamilles::FAMILLE_NEGOCIANT || $this->getFamille() == EtablissementFamilles::FAMILLE_NEGOCIANT_PUR);
    }

    public function getFamille() {
        if($this->declarant->famille) {

            return $this->declarant->famille;
        }

        if($this->getDefinition()->exist('famille')) {
            if (!$this->exist('famille') ) {
                $this->add('famille', $this->getEtablissement()->getFamille());
    	    }

            return $this->_get('famille');
        }

        return $this->getEtablissement()->getFamille();
    }

    /*     * * ARCHIVAGE ** */

    public function getNumeroArchive() {

        return $this->_get('numero_archive');
    }

    public function isArchivageCanBeSet() {

        return $this->isValidee();
    }

    /*     * * FIN ARCHIVAGE ** */

    /*     * * DROIT ** */

    public function storeDroits() {
        foreach ($this->getProduitsDetails($this->teledeclare) as $detail) {
            $detail->storeDroits();
        }
    }

    /*     * * FIN DROIT ** */

    /*     * * CRDS ** */

    public function addCrdRegimeNode($crdNode) {
        $this->add('crds', array($crdNode => array()));
    }

    public function switchCrdRegime($newCrdRegime = EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU){

        $to_removes = array();
        foreach ($this->getOrAdd('crds') as $regime => $crds) {
            if ($newCrdRegime != $regime) {
                $to_removes[$regime] = $crds;
            }
        }

        foreach ($to_removes as $removeRegime => $crds) {
            $this->getOrAdd('crds')->remove($removeRegime);
            if(!$this->getOrAdd('crds')->exist($newCrdRegime)) {
                $this->getOrAdd('crds')->add($newCrdRegime, $crds);
            }
        }
        foreach ($this->getProduits() as $produit) {
          $this->switchDetailsCrdRegime($produit,$newCrdRegime,DRM::DETAILS_KEY_SUSPENDU);
          $this->switchDetailsCrdRegime($produit,$newCrdRegime,DRM::DETAILS_KEY_ACQUITTE);
          }
        }

private function switchDetailsCrdRegime($produit,$newCrdRegime, $typeDrm = DRM::DETAILS_KEY_SUSPENDU)
{
    $mvtTypes = array('entrees','sorties');
    foreach ($produit->getProduitsDetails(true,$typeDrm) as $detailsKey => $details) {
          $detailsConfig = $details->getConfig();
          foreach ($mvtTypes as $mvtType){
                $toRemove = array();
                foreach($details->get($mvtType) as $key => $value) {
                        if(preg_match('/_details/',$key)){
                            continue;
                        }
                        if(!$detailsConfig->get($mvtType)->exist($key)){
                            continue;
                        }
                        $detailConf = $detailsConfig->get($mvtType)->get($key);
                        if($detailConf && $detailConf->exist('switch_regime'))
                        {
                            if((($detailConf->douane_type == DRMClient::CRD_TYPE_SUSPENDU) && ($newCrdRegime == EtablissementClient::REGIME_CRD_COLLECTIF_ACQUITTE))
                                || (($detailConf->douane_type == DRMClient::CRD_TYPE_ACQUITTE)
                                    && (($newCrdRegime == EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU) || ($newCrdRegime == EtablissementClient::REGIME_CRD_PERSONNALISE)))){
                              $detailConfCorrespondance = $detailConf->get('switch_regime');
                              $details->get($mvtType)->add($detailConfCorrespondance,$value);
                                $toRemove[] = $key;
                            }
                        }
                    }
                    foreach ($toRemove as $keyRemove) {
                        $details->get($mvtType)->remove($keyRemove);
                    }
            }
    }
}

    public function getAllCrds() {
        if ($this->exist('crds') && $this->crds) {
            return $this->crds;
        }
        return array();
    }

    public function getCrds() {
        if(!$this->exist('crds')) {

            return $this->add('crds');
        }

        return $this->_get('crds');
    }

    public function updateStockFinDeMoisAllCrds() {
        $result = array();
        if ($this->exist('crds') && $this->crds) {
            foreach ($this->crds as $regime => $crdsRegime) {
                foreach ($crdsRegime as $nodeName => $crd) {
                    $crd->udpateStockFinDeMois();
                    $result[$regime . '_' . $nodeName] = $crd;
                    if (($crd->exist('entrees_autres')  && $crd->entrees_autres) || ($crd->exist('sorties_autres') && $crd->sorties_autres)) {
                        $crd->add('observations');
                    }
                    if (($crd->exist('entrees_autres')  && !$crd->entrees_autres) && ($crd->exist('sorties_autres') && !$crd->sorties_autres)) {
                        $crd->remove('observations');
                    }
                }
            }
        }
        return $result;
    }

    public function getAllCrdsByRegimeAndByGenre() {
        $all_crd = $this->getAllCrds();
        $allCrdByRegimeAndByGenre = array();
        foreach ($all_crd as $regime => $crdAllGenre) {
            $allCrdByRegimeAndByGenre[$regime] = array();
            if (count($crdAllGenre)) {
                foreach ($crdAllGenre as $key => $crd) {
                    if (!array_key_exists($crd->genre, $allCrdByRegimeAndByGenre[$regime])) {
                        $allCrdByRegimeAndByGenre[$regime][$crd->genre] = array();
                    }
                    $allCrdByRegimeAndByGenre[$regime][$crd->genre][$key] = $crd;
                }
            }
        }
        return $allCrdByRegimeAndByGenre;
    }

    public function getRegimesCrds() {
        $all_crd = $this->getAllCrds();
        $regimes = array();
        foreach ($all_crd as $regime => $crdAllGenre) {
            $regimes[] = $regime;
        }
        return $regimes;
    }

    public function nbTotalCrdsTypes() {
        $total_crds = 0;
        foreach ($this->getAllCrdsByRegimeAndByGenre() as $regime => $crdsByRegime) {
            foreach ($crdsByRegime as $regime => $crds) {
                $total_crds += count($crds);
            }
        }
        return $total_crds;
    }

    public function hasManyCrds() {
        return $this->nbTotalCrdsTypes() > 0;
    }

    public function initCrds() {
        $toRemoves = array();
        $allCrdsByRegimeAndByGenre = $this->getAllCrdsByRegimeAndByGenre();
        foreach ($allCrdsByRegimeAndByGenre as $regime => $allCrdsByRegime) {
            foreach ($allCrdsByRegime as $genre => $crdsByRegime) {
                foreach ($crdsByRegime as $key => $crd) {
                    $crd->stock_debut = $crd->stock_fin;
                    $crd->entrees_achats = null;
                    $crd->entrees_retours = null;
                    $crd->entrees_excedents = null;
                    $crd->entrees_autres = null;
                    $crd->sorties_utilisations = null;
                    $crd->sorties_destructions = null;
                    $crd->sorties_manquants = null;
                    $crd->sorties_autres = null;
                }
            }
        }
    }

    public function initProduitsAutres($isTeledeclarationMode){
        if ($this->isNegoce()) {
            return;
        }
      foreach ($this->getConfigProduits($isTeledeclarationMode) as $hash => $produit) {
        if(preg_match("|/declaration/certifications/AUTRES|",$hash)){
            if(preg_match("/(DPLC|LIES)/",$hash)){
              $this->addProduit($hash, DRM::DETAILS_KEY_SUSPENDU);
            }
        }
      }
    }

    public function cleanDetails() {
        $this->declaration->cleanDetails();
    }

    public function cleanCrds() {
        $toRemoves = array();
        $allCrdsByRegimeAndByGenre = $this->getAllCrdsByRegimeAndByGenre();

        foreach ($allCrdsByRegimeAndByGenre as $regime => $allCrdsByRegime) {
            foreach ($allCrdsByRegime as $genre => $crdsByRegime) {
                foreach ($crdsByRegime as $key => $crd) {
                    $count_entree = $crd->entrees_achats + $crd->entrees_retours + $crd->entrees_excedents + $crd->entrees_autres + $crd->stock_fin + $crd->stock_debut;
                    if ($crd->stock_fin <= 0 && $crd->stock_debut <= 0 && !$count_entree) {
                        $toRemoves[] = $regime . '/' . $key;
                    }
                }
            }
        }
        foreach ($toRemoves as $toRemove) {
            $this->crds->remove($toRemove);
        }
    }

    public function crdsInitDefault() {

        if (!$this->exist('crds') || (!$this->crds)) {
            $this->add('crds');
        }
        $regimesCrd = ($this->getEtablissement()->exist('crd_regime')) ? $this->getEtablissement()->getCrdRegimeArray() : null;
        if ($regimesCrd) {
          foreach ($regimesCrd as $regimeCrd) {
            $this->crds->getOrAdd($regimeCrd)->crdsInitDefault($this->getAllCrdGenres());
          }
        }
    }

    public function getAllCrdGenres() {
        $genres = array();
        foreach ($this->getProduitsDetails(true) as $hash => $detail) {
            $genre = $detail->getCepage()->getCouleur()->getLieu()->getMention()->getAppellation()->getGenre()->getConfig();
            if (preg_match('/(cognac|armagnac)/i', $detail->getLibelle())) {
                $genres['COGNAC-ARMAGNAC'] = 'COGNAC-ARMAGNAC';
            } elseif($detail->isCodeDouanePI()) {
                $genres['PI'] = 'PI';
            } elseif($detail->isCodeDouaneAlcool()) {
                $genres['ALCOOLS'] = 'ALCOOLS';
            } elseif ($genre->getKey() == 'TRANQ' || $genre->getKey() == 'DEFAUT') {
                $genres['TRANQ'] = 'TRANQ';
            } else {
                $genres['MOUSSEUX'] = 'MOUSSEUX';
            }
        }
        return $genres;
    }

    /*     * * FIN CRDS ** */

    /**     * ADMINISTRATION ** */
    public function clearAnnexes() {
        if ($this->exist('documents_annexes') && $this->documents_annexes && count($this->documents_annexes)) {
            $this->remove('documents_annexes');
            $this->add('documents_annexes');
        }
        if ($this->exist('releve_non_apurement') && $this->releve_non_apurement && count($this->releve_non_apurement)) {
            $this->remove('releve_non_apurement');
            $this->add('releve_non_apurement');
        }

        if ($this->exist('quantite_sucre') && $this->quantite_sucre && count($this->quantite_sucre)) {
            $this->quantite_sucre = null;
        }
        if ($this->exist('observations') && $this->observations && count($this->observations)) {
            $this->observations = null;
        }
        if($this->exist('transmission_douane')){
            $this->remove('transmission_douane');
        }
    }

    public function cleanAnnexes() {
        $documents_annexes_to_remove = array();
        if ($this->exist('documents_annexes') && $this->documents_annexes && count($this->documents_annexes)) {
            foreach ($this->documents_annexes as $type_doc => $docNode) {
                if (!$docNode->debut && !$docNode->fin) {
                    $documents_annexes_to_remove[] = $type_doc;
                }
            }
        }
        $releve_non_apurement_to_remove = array();
        if ($this->exist('releve_non_apurement') && $this->releve_non_apurement && count($this->releve_non_apurement)) {
            foreach ($this->releve_non_apurement as $key => $nonApurementNode) {
                if (!$nonApurementNode->numero_document && !$nonApurementNode->date_emission && !$nonApurementNode->numero_accise) {
                    $releve_non_apurement_to_remove[] = $key;
                }
            }
        }
        foreach ($documents_annexes_to_remove as $key_to_remove) {
            $this->documents_annexes->remove($key_to_remove);
        }
        foreach ($releve_non_apurement_to_remove as $key_to_remove) {
            $this->releve_non_apurement->remove($key_to_remove);
        }
    }

    public function cleanReserve() {
        foreach ($this->getProduits() as $produit => $value) {
            if ($value->exist('reserve_interpro') && $value->reserve_interpro == 0) {
                $value->remove('reserve_interpro');
            }
        }
    }

    public function initReleveNonApurement() {
        $releveNonApurement = $this->getOrAdd('releve_non_apurement');
        if (!count($releveNonApurement)) {
            $releveNonApurement->addEmptyNonApurement();
        }
    }

    public function hasReleveNonApurement() {
      if(!$this->exist('releve_non_apurement')){
        return false;
      }
      if(!count($this->get('releve_non_apurement'))){
        return false;
      }
      foreach ($this->get('releve_non_apurement') as $nonApurement) {
        if($nonApurement->get("numero_document") && $nonApurement->get("date_emission") && $nonApurement->get("numero_accise")){
          return true;
        }
      }
      return false;
    }

    public function hasAnnexes() {
        $nodeAnnexe = $this->exist('documents_annexes') && count($this->documents_annexes);
        if (!$nodeAnnexe)
            return false;
        foreach ($this->documents_annexes as $annexe) {
            if ($annexe->fin || $annexe->debut) {
                return true;
            }
        }
        return false;
    }

    /*     * * FIN ADMINISTRATION ** */

    /**     * FAVORIS ** */
    public function buildFavoris($drmBase = null) {
        if ($drmBase && $drmBase->exist('favoris')) {
            foreach($drmBase->favoris as $d => $es) {
                foreach($es as $entree_ou_sortie => $detail) {
                    foreach($detail as $k => $v) {
                        $this->getOrAdd('favoris')->getOrAdd($d)->getOrAdd($entree_ou_sortie)->add($k, $v);
                    }
                }
            }
        }
        if (!$this->exist('favoris')) {
            foreach ($this->drmDefaultFavoris() as $key => $value) {
                $keySplitted = explode('/', $key);
                $this->getOrAdd('favoris')->getOrAdd($keySplitted[0])->getOrAdd($keySplitted[1])->add($keySplitted[2], $value);
            }
        }
    }

    public function getAllFavoris() {
        if ($this->exist('favoris') && $this->favoris) {
            return $this->favoris;
        }
        return $this->drmDefaultFavoris();
    }

    public function drmDefaultFavoris() {
        $configuration = $this->getConfig();
        $configurationFields = array();
        foreach ($configuration->libelle_detail_ligne as $typedetail => $detail) {
            foreach ($detail as $type => $libelles) {
                foreach ($libelles as $libelleHash => $libelle) {
                    $configurationFields[$typedetail.'/'.$type . '/' . $libelleHash] = $libelle->libelle;
                }
            }
        }
        $drm_default_favoris = $configuration->get('mvts_favoris');
        foreach ($configurationFields as $key => $value) {
            if (!in_array(str_replace('/', '_', $key), $drm_default_favoris->toArray(0, 1))) {
                unset($configurationFields[$key]);
            }
        }
        return $configurationFields;
    }

    /*     * * FIN FAVORIS ** */

    /*     * * SOCIETE ** */

    public function storeSociete() {
        $societe = $this->getEtablissement()->getSociete();
        $drm_societe = $this->add('societe');
        $drm_societe->add('raison_sociale', $societe->raison_sociale);
        if(count($societe->getEtablissementsObj(false)) >= 2) {
            $drm_societe->add('raison_sociale', $this->declarant->nom);
        }
        $drm_societe->add('siret', $societe->siret);
        $drm_societe->add('code_postal', $societe->siege->code_postal);
        $drm_societe->add('adresse', $societe->siege->adresse);
        $drm_societe->add('commune', $societe->siege->commune);
        $drm_societe->add('email', $societe->getEmailTeledeclaration());
        $drm_societe->add('telephone', $societe->telephone);
        $drm_societe->add('fax', $societe->fax);
        $drm_societe->add('paiement_douane_moyen', ($societe->exist('paiement_douane_moyen')) ? $societe->paiement_douane_moyen : null);
        $drm_societe->add('paiement_douane_frequence', ($societe->exist('paiement_douane_frequence')) ? $societe->paiement_douane_frequence : null);
    }

    public function getCoordonneesSociete() {
        if (!$this->exist('societe') || is_null($this->societe)) {
            $this->storeSociete();
        }
        return $this->societe;
    }

    public function getSocieteInfos() {
        $societeInfos = new stdClass();
        if (!$this->exist('societe') || is_null($this->societe) || is_null($this->societe->raison_sociale)) {
            $societe = $this->getEtablissement()->getSociete();
            $societeInfos->raison_sociale = $societe->raison_sociale;
            $societeInfos->siret = $societe->siret;
            $societeInfos->code_postal = $societe->siege->code_postal;
            $societeInfos->adresse = $societe->siege->adresse;
            $societeInfos->commune = $societe->siege->commune;
            $societeInfos->email = $societe->getEmailTeledeclaration();
            $societeInfos->telephone = $societe->telephone;
            $societeInfos->fax = $societe->fax;
            return $societeInfos;
        }
        return $this->societe;
    }

    /*     * * FIN SOCIETE ** */

    /** Droit de circulation douane */
    public function generateDroitsDouanes() {
        $this->getOrAdd('droits')->getOrAdd('douane')->initDroitsDouane();
        foreach ($this->getProduitsDetails(true) as $produitDetail) {
            $produitDetail->updateDroitsDouanes();
        }
    }

    public function getDroitsDouane() {
        return $this->droits->douane;
    }

    public function initDroitsDouane() {
        try {
            foreach ($this->droits->douane as $key_douane_genre => $droitDouane) {
                $droitDouane->clearDroitDouane();
            }
        } catch (Exception $e) {

        }
    }

    public function hasPaiementDouane(){
      if(!$this->declaratif){

        return false;
      }

      if(!$this->societe->exist('paiement_douane_frequence') && !$this->societe->exist('paiement_douane_moyen')){
        return false;
      }
      if(!$this->societe->get('paiement_douane_frequence') && !$this->societe->get('paiement_douane_moyen')){
        return false;
      }
      if($this->societe->get('paiement_douane_frequence') == DRMPaiement::FREQUENCE_ANNUELLE){
        $flag = true;
        foreach ($this->droits->douane as $key => $node) {
          if(!$node->cumul){
            $flag = false;
            break;
          }
        }
        if(!$flag){
          return false;
        }
      }
      return true;
    }


    public function setPaiementDouaneFrequence($p){
      $this->societe->paiement_douane_frequence = $p;
      $soc = $this->getEtablissement()->getSociete();
      if (!$soc->exist('paiement_douane_frequence') || !$soc->get('paiement_douane_frequence') != $p) {
        $soc->add('paiement_douane_frequence', $p);
        $soc->save();
      }
    }

    /** Fin Droit de circulation douane */

    public function isCreationEdi(){
      return $this->etape == DRMClient::ETAPE_VALIDATION_EDI;
    }


    /*
    * Observations
    */
    public function addObservationProduit($hash, $observation)
    {
      if ($this->exist($hash)) {
        $produit = $this->get($hash);
        $produit->observations = $observation;
      }
    }
    public function addReplacementDateProduit($hash, $date)
    {
      if ($this->exist($hash)) {
        $produit = $this->get($hash);
        $produit->replacement_date = $date;
      }
    }

    public function getExportableObservations() {
      return 'observations';
    }

    public function hasObservations(){
      foreach ($this->getProduitsDetails($this->teledeclare) as $hash => $detail) {
        if($detail->exist('observations')){
          return true;
        }
    }

        foreach ($this->crds as $regime => $crdsRegime) {
            foreach ($crdsRegime as $nodeName => $crd) {
                if ($crd->exist('observations')) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getObservationsArray(){
      $observations = array();
      foreach ($this->getProduitsDetails($this->teledeclare) as $hash => $detail) {
        if($detail->exist('observations') && $detail->get('observations')){
          $observations[$detail->getLibelle()] = $detail->get('observations');
        }
      }
      foreach ($this->crds as $regime => $crdsRegime) {
          foreach ($crdsRegime as $nodeName => $crd) {
            if ($crd->exist('observations') && $crd->get('observations')) {
                $observations[$crd->getCompletLibelle()] = $crd->get('observations');
            }
          }
      }
      return $observations;
    }

    public function getReplacementDateArray(){
      $dates = array();
      foreach ($this->getProduitsDetails($this->teledeclare) as $hash => $detail) {
        if($detail->exist('replacement_date') && $detail->get('replacement_date')){
          $dates[$detail->getLibelle()] = $detail->get('replacement_date');
        }
      }
      return $dates;
    }


    /*
    * Fin Observations
    */

    /**
    * Tavs
    */
    public function addTavProduit($hash, $tav)
    {
      if ($this->exist($hash)) {
        $produit = $this->get($hash);
        $produit->add("tav",$tav);
      }
    }

    public function hasTavs(){
      foreach ($this->getProduitsDetails($this->teledeclare) as $hash => $detail) {
        if($detail->exist('tav')){
          return true;
        }
      }
        return false;
    }

    public function getTavsArray(){
      $tavs = array();
      foreach ($this->getProduitsDetails($this->teledeclare) as $hash => $detail) {

        if($detail->exist('tav') && $detail->get('tav')){
          $tavs[$detail->getLibelle().' ('.$detail->getTypeDRMLibelle().')'] = $detail->get('tav');
        }
      }
      return $tavs;
    }
    /**
    * Fin Tavs
    */


    public function allLibelleDetailLigneForDRM() {
        $config = $this->getConfig();
        $libelles_detail_ligne = $config->libelle_detail_ligne;
        $toRemove = array();
        foreach ($libelles_detail_ligne as $typedetail => $typedetaillibelle) {
            foreach ($typedetaillibelle as $catKey => $cat) {
                foreach ($cat as $typeKey => $detail) {
                    if (!$config->declaration->get($typedetail)->get($catKey)->exist($typeKey) || !$config->declaration->get($typedetail)->get($catKey)->get($typeKey)->isWritableForEtablissement($this->getEtablissement(), $this->teledeclare)) {
                        $toRemove[] = $typedetail. '/' . $catKey . '/' . $typeKey;
                    }
                }
            }
        }
        foreach ($toRemove as $removeNode) {

            $libelles_detail_ligne->remove($removeNode);
        }
        return $libelles_detail_ligne;
    }

    public function isCreationAuto(){
      return $this->type_creation == DRMClient::DRM_CREATION_AUTO;
    }

    public function getXML() {
      if (!function_exists('get_partial')) {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Partial'));
      }
      $partial = ($this->isNegoce())? 'drm_xml/xmlnegoce' : 'drm_xml/xml';
      return get_partial($partial, array('drm' => $this));
    }

    public function getXMLRetour() {
        if (!$this->exist('_attachments') || !$this->_attachments->exist('drm_retour.xml'))
          return "";
        $uri = $this->getAttachmentUri('drm_retour.xml');
        if ($uri) {
          return file_get_contents($uri);
        }
        return "";
    }

    public function storeXMLRetour($xml) {
        if (!$xml) {
          throw new sfException('XML empty');
        }
        if (md5($this->getXMLRetour()) == md5($xml)) {
          return false;
        }

        $tmp = tempnam('/tmp', 'attachment_retour');
        file_put_contents($tmp, $xml);
        $this->storeAttachment($tmp, 'text/xml', 'drm_retour.xml');
        unlink($tmp);
        return true;
    }

    public function getXMLComparison() {
        return new DRMCielCompare($this->getXMLRetour(), $this->getXML());
    }

    public function areXMLIdentical() {
      $comp = $this->getXMLComparison();
      return !$comp->hasDiff();
    }

    public function transferToCiel() {
        if($this->getEtablissementObject()->no_accises) {
            $this->declarant->no_accises = $this->getEtablissementObject()->no_accises;
        }
      $xml = $this->getXML();
      $service = new CielService();
      return $service->transferAndStore($this, $xml);
    }

    public function hasBeenTransferedToCiel() {
      return ($this->exist('transmission_douane') && $this->transmission_douane->exit('xml') && $this->transmission_douane->success);
    }

    public function getTransmissionDate() {
      if ($this->exist('transmission_douane')) {
        return date('d/m/Y', strtotime($this->transmission_douane->horodatage));
      }
      return "";
    }

    public function getTransmissionErreur() {
      if ($this->exist('transmission_douane')) {
        return preg_replace('/<[^>]*>/', '', $this->transmission_douane->xml);
      }
      return "";
    }

    public function initTransmission() {
        if ($this->exist('transmission_douane') || !$this->isTeledeclare()) {
            return;
        }
        $this->add('transmission_douane');
        $this->transmission_douane->success = false;
        if ($this->isNegoce()) {
            $this->transmission_douane->xml = 'Pas de transmission : DRM Négociant pur';
        } else {
            $this->transmission_douane->xml = 'Pas de transmission';
        }
        if ($this->valide->identifiant) {
            $this->transmission_douane->xml .= ' ('.$this->valide->identifiant.')';
        }
    }

    public function getDetailsByHash($hash_details_or_cepage){
      if($this->exist($hash_details_or_cepage)){
        $node_details_or_cepage = $this->get($hash_details_or_cepage);
        if($node_details_or_cepage instanceof DRMCepage){
          return $node_details_or_cepage->getDetails()->get(self::DEFAULT_KEY);
        }elseif($node_details_or_cepage instanceof DRMDetail){
          return $node_details_or_cepage;
        }
      }
      throw new sfException("La Hash du mvt $hash_details_or_cepage n'a pas été trouvée dans la DRM");
    }

    public function hasExportableProduitsAcquittes(){
      return count($this->getProduitsDetails(true,self::DETAILS_KEY_ACQUITTE));
    }

    public function getTotalStockSuspendu(){
      $total = 0.0;
      foreach ($this->getProduitsDetails(true,self::DETAILS_KEY_SUSPENDU) as $produit) {
        $total += $produit->getTotal();
      }
      return $total;
    }

    public function getTotalStockAcquitte(){
      $total = 0.0;
      foreach ($this->getProduitsDetails(true,self::DETAILS_KEY_ACQUITTE) as $produit) {
        $total += $produit->getTotal();
      }
      return $total;
    }

    public function hasStatsEuropeennes() {
      return (($this->declaratif->statistiques->jus != null) || ($this->declaratif->statistiques->mcr != null) || ($this->declaratif->statistiques->vinaigre != null));
    }

    public function hasMatierePremiere(){
      foreach($this->getProduitsDetails() as $detail){
          if($detail->isMatierePremiere()){ return true; }
      }
      return false;
    }

    public function hasWritableTAV() {
        foreach ($this->getProduitsDetails() as $hash => $detail) {
            if($detail->exist('tav')){
                if ($detail->hasStockFinDeMoisDRMPrecedente()) {
                    return false;
                }
                if ($detail->isAlcoolPur()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function isNegoce() {
        return $this->declarant->famille == EtablissementFamilles::FAMILLE_NEGOCIANT_PUR;
    }

    public function canSetStockDebutMois() {
       return (!$this->hasPrecedente() || $this->changedToTeledeclare() || $this->changedImportToCreation() || $this->isMoisOuvert());
    }

    public function isMoisOuvert() {
      $mois = ($this->getEtablissementObject())? $this->getEtablissementObject()->getMoisToSetStock() : DRMPaiement::NUM_MOIS_DEBUT_CAMPAGNE;
      return (DRMClient::getInstance()->getMois($this->periode) == $mois)? true : false;
    }

}
