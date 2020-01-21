<?php

/**
 * Model for Vrac
 *
 */
class SV12 extends BaseSV12 implements InterfaceMouvementDocument, InterfaceVersionDocument, InterfaceDeclarantDocument, InterfaceArchivageDocument, InterfaceDroitDocument, InterfaceValidableDocument {

    protected $mouvement_document = null;
    protected $version_document = null;
    protected $declarant_document = null;
    protected $archivage_document = null;

    public function  __construct() {
        parent::__construct();
        $this->initDocuments();
    }

    public function __clone() {
        parent::__clone();
        $this->initDocuments();
    }

    protected function initDocuments() {
        $this->mouvement_document = new MouvementDocument($this);
        $this->version_document = new VersionDocument($this);
        $this->declarant_document = new DeclarantDocument($this);
        $this->archivage_document = new ArchivageDocument($this);
    }

    public function constructId() {
        $this->storeDeclarant();
        $this->set('_id', SV12Client::getInstance()->buildId($this->identifiant,
							   $this->periode,
							   $this->version));
    }

    //Par homogénéisation, la période vaut la campagne
    public function setPeriode($p) {
      $this->campagne = $p;
      return $this->_set('periode', $p);
    }

    //A conserver pour gestion des abstracts de InterfaceArchivageDoc
    public function getCampagne() {
      return $this->_get('campagne');
    }

    public function getFirstDayOfPeriode() {
       return substr($this->periode, 0,4).'-'.substr($this->periode, 4,2).'-01';
    }

    public function getPeriodeAndVersion() {

        return SV12Client::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function storeContrats() {
        $contratsView = SV12Client::getInstance()->findContratsByEtablissementAndCampagne($this->identifiant, $this->campagne);
        foreach ($contratsView as $contratView)
        {
            $idContrat = preg_replace('/VRAC-/', '', $contratView->value[VracClient::VRAC_VIEW_NUMCONTRAT]);
            $this->updateContrats($idContrat,$contratView->value);
        }
    }

    public function getContratsByVendeur() {
        $contrats = array();
        foreach($this->contrats as $key => $c) {
            if ($c->vendeur_nom) {
                $contrats[$c->vendeur_nom." ".$c->produit_hash." ".$key] = $c;
            }else{
                $contrats[isset($c->commentaire) ? $c->commentaire : "SANSVITI" ." ".$c->produit_hash." ".$key] = $c;
            }
        }
        ksort($contrats);
        return $contrats;
    }

    public function getContratsNonSaisis() {
        $contrats = array();
        foreach($this->contrats as $key => $c) {
            if (!$c->isSaisi()) {
                $contrats[$key] = $c;
            }
        }

        return $contrats;
    }

    public function getConfig() {

        return ConfigurationClient::getConfiguration($this->getDate());
    }

    public function getConfigProduits() {
      //  return $this->getConfig()->formatProduits($this->getFirstDayOfPeriode(), "%format_libelle% (%code_produit%)",array(_ConfigurationDeclaration::ATTRIBUTE_CVO_FACTURABLE));
        return $this->getConfig()->declaration->formatProduits($this->getDocument()->getFirstDayOfPeriode(),
                                                  "INTERPRO-inter-loire",
                                                  $this->getDocument()->getDepartement(),
                                                  "%format_libelle% (%code_produit%)",
                                                  $this->getConfigProduitsAttributes(true));
    }

    public function getDepartement() {
        if ($this->declarant->code_postal) {
            return substr($this->declarant->code_postal, 0, 2);
        }

        return null;
    }

    public function getConfigProduitsAttributes() {
        $attributes = array(_ConfigurationDeclaration::ATTRIBUTE_CVO_ACTIF);

        return $attributes;
    }

    public function isValidee() {

        return $this->valide && $this->valide->exist('date_saisie') &&
            ($this->valide->date_saisie) && (in_array($this->valide->statut, array(SV12Client::STATUT_VALIDE, SV12Client::STATUT_VALIDE_PARTIEL)));
    }

    public function isBrouillon() {

        return ($this->valide->date_saisie) && ($this->valide->statut==SV12Client::STATUT_BROUILLON);
    }

    public function updateContrats($num_contrat, $contrat) {
        if ($this->contrats->exist($num_contrat)) {
    	    if($contrat[VracClient::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ANNULE) {
                return $this->contrats->remove($num_contrat);
            }

	    $this->contrats->get($num_contrat)->updateFromView($contrat);

            return;
        }

        if (!$contrat) {

           throw new acCouchdbException(sprintf("Le Contrat \"%s\" n'existe pas!", $num_contrat));
        }

        if(!in_array($contrat[VracClient::VRAC_VIEW_STATUT], VracClient::$statuts_vise)) {

            return;
        }

        $contratObj = new stdClass();
        $contratObj->contrat_numero = $num_contrat;
        $contratObj->contrat_type = $contrat[VracClient::VRAC_VIEW_TYPEPRODUIT];
        $contratObj->produit_libelle = $this->getConfig()->get($contrat[VracClient::VRAC_VIEW_PRODUIT_ID])->getLibelleFormat();
        $contratObj->produit_hash = $contrat[VracClient::VRAC_VIEW_PRODUIT_ID];
        $contratObj->vendeur_identifiant = $contrat[VracClient::VRAC_VIEW_VENDEUR_ID];
        $contratObj->vendeur_nom = $contrat[VracClient::VRAC_VIEW_VENDEUR_NOM];
        $contratObj->volume_prop = $contrat[VracClient::VRAC_VIEW_VOLPROP];
        $this->contrats->add($num_contrat, $contratObj);
    }

    public function addContrat($vrac) {
        if (!$vrac) {
            throw new acCouchdbException(sprintf("Le Contrat n'existe pas!"));
        }

        if(!$vrac->isValidee()) {

            return;
        }

        $contrat = new stdClass();

        $config_produit = $vrac->getProduitObject();
        $contrat->contrat_numero = $vrac->numero_contrat;
        $contrat->contrat_type = $vrac->type_transaction;
        $contrat->produit_libelle = $config_produit->getLibelleFormat("%format_libelle%");
        $contrat->produit_hash = $config_produit->getHash();
        $contrat->vendeur_identifiant = $vrac->vendeur_identifiant;
        $contrat->vendeur_nom = $vrac->vendeur->nom;
        $contrat->volume_prop = $vrac->volume_propose;

        return $this->contrats->add($vrac->numero_contrat, $contrat);
    }

    public function updateVracs() {
        $contrats_to_save = array();

        foreach ($this->contrats as $c) {
            if ($c->getVrac()) {
                  if ($c->getVrac() && !$c->getVrac()->isSolde()) {
                    $contrats_to_save[] = $c->getVrac();
                 }
            }
        }

        foreach($contrats_to_save as $vrac)  {
            $vrac->updateVolumesEnleves();
            $vrac->save();
        }
    }

    public function isAllContratsCanBeSoldable() {
        foreach ($this->contrats as $c) {
            if (!$c->canBeSoldable()) {
                return false;
            }
        }

        return true;
    }

    public function getVolumeTotal() {
      return $this->totaux->volume_raisins + $this->totaux->volume_mouts + $this->totaux->volume_ecarts;
    }

    public function updateTotaux() {
        $this->remove('totaux');
        $this->add('totaux');

        $this->totaux->volume_raisins = 0;
        $this->totaux->volume_mouts = 0;
        $this->totaux->add('sv12_raisins', 0);
        $this->totaux->add('sv12_mouts', 0);

        foreach ($this->contrats as $contrat) {
            if(!$this->totaux->produits->exist($contrat->produit_libelle)) {
                $noeud = $this->totaux->produits->add($contrat->produit_libelle);
                $noeud->produit_hash = $contrat->produit_hash;
                $noeud->volume_raisins = 0;
                $noeud->volume_mouts = 0;
                $noeud->add('sv12_mouts', 0);
                $noeud->add('sv12_raisins', 0);
            } else {
                $noeud = $this->totaux->produits->get($contrat->produit_libelle);
            }

            if ($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS) {
                $noeud->volume_raisins += $contrat->volume;
                $this->totaux->volume_raisins += $contrat->volume;
                if ($contrat->exist('volume_sv12')) {
                    $noeud->sv12_raisins += $contrat->volume_sv12;
                    $this->totaux->sv12_raisins += $contrat->volume_sv12;
                }
            } elseif($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS) {
                $noeud->volume_mouts += $contrat->volume;
                $this->totaux->volume_mouts += $contrat->volume;
                if ($contrat->exist('volume_sv12')) {
                    $noeud->sv12_mouts += $contrat->volume_sv12;
                    $this->totaux->sv12_mouts += $contrat->volume_sv12;
                }
            } else {
                $noeud->volume_ecarts += $contrat->volume;
                $this->totaux->volume_ecarts += $contrat->volume;
	        }
        }
        if (!$this->totaux->sv12_raisins && !$this->totaux->sv12_mouts) {
            $this->totaux->remove('sv12_raisins');
            $this->totaux->remove('sv12_mouts');
            foreach($this->totaux->produits as $l => $p) {
                $p->remove('sv12_mouts');
                $p->remove('sv12_raisins');
            }
        }
    }

    public function updateVolume($num_contrat,$volume) {
        $this->contrats[$num_contrat]->volume = $volume;
        return $this->contrats[$num_contrat];
    }

    public function updateVolumeFromSV12($num_contrat, $volume, $commentaire = null) {
        $this->contrats[$num_contrat]->add('volume_sv12', $volume);
        $this->contrats[$num_contrat]->add('commentaire', $commentaire);
        if (!$this->contrats[$num_contrat]->volume) {
            $this->contrats[$num_contrat]->volume = $volume;
        }
        return $this->contrats[$num_contrat];
    }

    public function storeDates() {
        if (!$this->valide->date_saisie) {
           $this->valide->add('date_saisie', date('Y-m-d'));
        }
    }

    public function validate($options = array()) {
        if($this->isValidee()) {

            throw new sfException(sprintf("Cette SV12 est déjà validée"));
        }

        $this->generateMouvements();
        $this->updateTotaux();
        $this->storeDates();
        $this->storeDeclarant();

        $this->archivage_document->archiver();

        if($this->isAllContratsCanBeSoldable()) {
            $this->valide->statut = SV12Client::STATUT_VALIDE;
        } else {
            $this->valide->statut = SV12Client::STATUT_VALIDE_PARTIEL;
        }

    }

    public function devalide() {
        $this->clearMouvements();
        $this->valide->date_saisie = '';
        $this->valide->statut = SV12Client::STATUT_BROUILLON;
    }


    public function saveBrouillon() {
        $this->add('valide');
        $this->valide->add('date_saisie', date('Y-m-d'));
        $this->valide->add('statut', SV12Client::STATUT_BROUILLON);
        return $this->save();
    }

    public function getDate() {

        return SV12Client::getInstance()->buildDate($this->periode);
    }

    public function getSuivante() {

        return false;
    }

    protected function preSave() {
        $this->updateTotaux();
        $this->archivage_document->preSave();
	$this->region = $this->getEtablissementObject()->region;
    }

    public function delete() {
        if ($this->isValidee() || !$this->isMaster()) {

            throw new sfException("Impossible de supprimer une SV12 validée");
        }

        parent::delete();
    }

    public function __toString()
    {

        return SV12Client::getInstance()->getLibelleFromId($this->_id);
    }

    /**** VERSION ****/

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

    public function getPreviousVersion() {

       return $this->version_document->getPreviousVersion();
    }

    public function getMasterVersionOfRectificative() {
        return SV12Client::getInstance()->findMasterRectificative($this->identifiant,
                                                                 $this->periode,
                                                                 self::buildVersion($this->getRectificative() - 1, 0));
    }

    public function needNextVersion() {

       return $this->version_document->needNextVersion();
    }

    public function getMaster() {

        return $this->version_document->getMaster();
    }

    public function isMaster() {

        return $this->version_document->isMaster();
    }

    public function findMaster() {

        return SV12Client::getInstance()->findMaster($this->identifiant, $this->periode);
    }

    public function findDocumentByVersion($version) {

        return SV12Client::getInstance()->find(SV12Client::getInstance()->buildId($this->identifiant, $this->periode, $version));
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

        return true;
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

        return $this->version_document->generateModificative();
    }

    public function generateNextVersion() {

        return false;
    }

    public function listenerGenerateVersion($document) {
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {

    }

    /**** FIN DE VERSION ****/

    /**** MOUVEMENTS ****/

    public function getMouvements() {

        return $this->_get('mouvements');
    }

    public function getMouvementsCalcule() {

        $mouvements = array();
        foreach($this->contrats as $contrat) {
            $mouvement_vendeur = $contrat->getMouvementVendeur();
            if ($mouvement_vendeur && $contrat->vendeur_identifiant) {
                $mouvements[$contrat->vendeur_identifiant][$mouvement_vendeur->getMD5Key()] = $mouvement_vendeur;
            }

            $mouvement_acheteur = $contrat->getMouvementAcheteur();
            if ($mouvement_acheteur) {
                $mouvements[$this->getDocument()->identifiant][$mouvement_acheteur->getMD5Key()] = $mouvement_acheteur;
            }
        }

        return $mouvements;
    }

    public function getMouvementsCalculeByIdentifiant($identifiant) {

       return $this->mouvement_document->getMouvementsCalculeByIdentifiant($identifiant);
    }

    public function generateMouvements() {

        return $this->mouvement_document->generateMouvements();
    }

    public function findMouvement($cle, $id = null){
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

    public function clearMouvements(){
        $this->remove('mouvements');
        $this->add('mouvements');
    }

    public function addSansContrat($etablissement, $raisinetmout, $hash_ref) {
        $sv12Contrat = $this->contrats->add(SV12Client::SV12_KEY_SANSCONTRAT.'-'.$etablissement->identifiant.'-'.$raisinetmout.str_replace('/', '-', $hash_ref));
        $sv12Contrat->updateNoContrat($this->getConfig()->get($hash_ref), array('vendeur_identifiant' => $etablissement->identifiant, 'vendeur_nom' => $etablissement->nom, 'contrat_type' => $raisinetmout));
        $sv12Contrat->add('commentaire', "Sans contrat");
        return $sv12Contrat;
    }

    public function addSansViti($hash_ref, $raisinetmout = null) {
        $sv12Contrat = $this->contrats->add(SV12Client::SV12_KEY_SANSVITI.'-'.$raisinetmout.str_replace('/', '-', $hash_ref));
        $sv12Contrat->updateNoContrat($this->getConfig()->get($hash_ref), array('vendeur_identifiant' => null, 'vendeur_nom' => null, 'contrat_type' => $raisinetmout));
        $sv12Contrat->add('commentaire', "Sans viti");
        return $sv12Contrat;
    }

    public function hasSansContratOrSansViti() {
        if($this->valide->statut != SV12Client::STATUT_VALIDE){
            return false;
        }
        foreach ($this->contrats as $key => $contrat) {
            if (substr($key, 0, strlen(SV12Client::SV12_KEY_SANSCONTRAT)) == SV12Client::SV12_KEY_SANSCONTRAT) {
                return true;
            }
            if (substr($key, 0, strlen(SV12Client::SV12_KEY_SANSVITI)) == SV12Client::SV12_KEY_SANSVITI) {
                return true;
            }
        }
        return false;
    }

    /**** FIN DES MOUVEMENTS ****/


    /**** DECLARANT ****/

    public function storeDeclarant() {
        $this->declarant_document->storeDeclarant();
    }

    public function getEtablissementObject() {
        return $this->getEtablissement();
    }

    public function getEtablissement() {

        return EtablissementClient::getInstance()->find($this->identifiant);
    }

    /**** FIN DES DECLARANT ****/

    /*** ARCHIVAGE ***/

     public function getNumeroArchive() {

        return $this->_get('numero_archive');
    }

    public function isArchivageCanBeSet() {

        return $this->isValidee();
    }

    /*** FIN ARCHIVAGE ***/

    /*** DROIT ***/
    public function storeDroits() {
        foreach($this->contrats as $detail) {
            $detail->storeDroits();
        }
    }
    /*** FIN DROIT ***/

    public function getSV12DouaneURL() {
        $campagne = preg_replace('/-.*/', '', $this->campagne);
        $id = "SV12-".$this->identifiant."-".$campagne;
        $client = acCouchdbManager::getClient();
        $douane = $client->find($id, acCouchdbClient::HYDRATE_JSON);
        foreach ($douane->_attachments as $key => $value) {
                if (preg_match('/xls/', $key)) {
                    $valid = $key;
                    continue;
                }
        }
        if (!$valid) {
            return;
        }
        return $client->dsn().$client->getAttachmentUri($douane, $valid);
    }

    public function importFromSV12Douane() {
        $sv12 = SV12Client::getInstance()->getSV12CSVArray($this);
        $delete_cvi = array();
        $conf = ConfigurationClient::getConfigurationByCampagne($this->campagne);
        foreach($this->contrats as $numcontrat => $contrat) {
            $cvi = $contrat->vendeur->cvi;
            $idhashtype = $contrat->produit_hash;
            if ($contrat->contrat_type == VracClient::TYPE_TRANSACTION_MOUTS) {
                $idhashtype .= '11';
            } elseif ($contrat->contrat_type == VracClient::TYPE_TRANSACTION_RAISINS) {
                $idhashtype .= '10';
            }
            if (!isset($sv12[$cvi][$idhashtype]) || count($sv12[$cvi][$idhashtype]) < 1) {
                $this->updateVolumeFromSV12($numcontrat, null, "douane non trouvée");
                continue;
            }
            if (count($sv12[$cvi][$idhashtype]) > 1) {
                $this->updateVolumeFromSV12($numcontrat, null, "Plusieurs douanes trouvées");
                continue;
            }
            $c = $sv12[$cvi][$idhashtype][0];
            if ($contrat->volume_prop && abs(preg_replace('/,/', '.', $c[21]) - $contrat->volume_prop) / $contrat->volume_prop < 0.10) {
                $this->updateVolumeFromSV12($numcontrat, $c[21]);
            }else{
                $contrat = $this->updateVolumeFromSV12($numcontrat, $c[21], "volume trop différent");
            }
            unset($sv12[$cvi][$idhashtype]);
            if (!count($sv12[$cvi])) {
                unset($sv12[$cvi]);
            }
        }
        foreach($sv12 as $cvi => $produits) {
            foreach($produits as $hash => $array) {
                foreach ($array as $csv) {
                    if ($conf->get($csv[27])->getDroitCVO($this->getPeriode())->getTaux() > 0) {
                        $e = EtablissementClient::getInstance()->findByCvi($cvi);
                        if ($e) {
                            $contrat = $this->addSansContrat($e,
                                                        ($csv[19] == 11) ? VracClient::TYPE_TRANSACTION_MOUTS : VracClient::TYPE_TRANSACTION_RAISINS,
                                                        $csv[27]);
                            $contrat->volume = $csv[21];
                            $contrat->add('volume_sv12', $csv[21]);
                        }else{
                            $contrat = $this->addSansViti($csv[27], ($csv[19] == 11) ? VracClient::TYPE_TRANSACTION_MOUTS : VracClient::TYPE_TRANSACTION_RAISINS);
                            $contrat->volume = $csv[21];
                            $contrat->add('volume_sv12', $csv[21]);
                            $contrat->add('commentaire', $csv[23]." : cvi non trouvé : [$cvi]");
                        }
                    }
                }
            }
        }
    }

    public function hasSV12DouaneImported() {
        return ($this->totaux->exist('sv12_mouts') + $this->totaux->exist('sv12_raisins'));
    }
}
