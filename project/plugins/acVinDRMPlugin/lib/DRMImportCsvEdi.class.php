<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMImportCsvEdi
 *
 * @author mathurin
 */
class DRMImportCsvEdi extends DRMCsvEdi {

    protected $configuration = null;
    protected $mouvements = array();
    protected $csvDoc = null;
    protected $fromEdi = false;

      public function __construct($file, DRM $drm = null, $fromEdi = false) {
            $this->fromEdi = $fromEdi;
            $this->initConf();
            if($this->fromEdi){
              parent::__construct($file, $drm);
              $drmInfos = $this->getDRMInfosFromFile();
              if(!$drmInfos){
                throw new sfException("Aucune DRM ne peut être initialisé le fichier csv n'a ni identifiant, ni periode");
              }
              try{
                $drm = DRMClient::getInstance()->findOrCreateFromEdiByIdentifiantAndPeriode($drmInfos['identifiant'],$drmInfos['periode'], true);
              }catch(sfException $e){
                echo "\"#Niveau erreur\";\"Numéro ligne de l'erreur\";\"Parametre en erreur \";\"Diagnostic\"\n";
                echo "Error;1;".$drmInfos['identifiant'].";Le numéro de compte n'est pas connu\n";
                return;
              }
            }

            if(is_null($this->csvDoc)) {
                $this->csvDoc = CSVClient::getInstance()->createOrFindDocFromDRM($file, $drm);
            }
            parent::__construct($file, $drm);
        }

        private function getDRMInfosFromFile(){
          if($this->getCsv()){
            foreach ($this->getCsv() as $keyRow => $csvRow) {
              if((KeyInflector::slugify($csvRow[self::CSV_TYPE]) == self::TYPE_CAVE)
              || (KeyInflector::slugify($csvRow[self::CSV_TYPE]) == self::TYPE_CRD)
              || (KeyInflector::slugify($csvRow[self::CSV_TYPE]) == self::TYPE_ANNEXE)){
                if (!preg_match('/^[0-9]{8}$/', KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]))) {
                  continue;
                }
                if (!preg_match('/^[0-9]{6}$/', KeyInflector::slugify($csvRow[self::CSV_PERIODE]))) {
                    continue;
                }
                return array('identifiant' => KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]), 'periode' => KeyInflector::slugify($csvRow[self::CSV_PERIODE]));
              }
            }
          }
          return null;
        }

        public function getDrm(){
          return $this->drm;
        }

        public function getCsvDoc() {

            return $this->csvDoc;
        }

        public function getCsvArrayErreurs(){
          $csvErreurs = array();
          $csvErreurs[] = array("#Niveau erreur","Numéro ligne de l'erreur","Parametre en erreur ","Diagnostic");
          if($this->getCsvDoc()->hasErreurs()){
            $erreursRows = $this->getCsvDoc()->getErreurs();
            foreach ($erreursRows as $erreur) {
              $erreurLevel = ($erreur->exist('level'))? CSVClient::$levelErrorsLibelle[$erreur->level] : "";
              $csvErreurs[] = array($erreurLevel,"".$erreur->num_ligne,"".$erreur->csv_erreur,$erreur->diagnostic);
            }
          }
          return $csvErreurs;
        }

        protected function initConf() {
            $this->configuration = ConfigurationClient::getCurrent();
            $this->mouvements = $this->buildAllMouvements();
        }

        public function getDocRows() {
            return $this->getCsv($this->csvDoc->getFileContent());
        }

        /**
         * CHECK DU CSV
         */
        public function checkCSV() {
            $this->csvDoc->clearErreurs();
            $this->checkCSVIntegrity();
            if ($this->csvDoc->hasErreurs()) {
                $this->csvDoc->setStatut(self::STATUT_ERREUR);
                $this->csvDoc->save();
                return;
            }
            // Check annexes
            $this->checkImportAnnexesFromCSV();
            // Check mouvements
            $this->checkImportMouvementsFromCSV();
            // Check Crds
            $this->checkImportCrdsFromCSV();

            if ($this->csvDoc->hasErreurs()) {
                $this->csvDoc->setStatut(self::STATUT_WARNING);
                $this->csvDoc->save();
                return;
            }
            $this->csvDoc->setStatut(self::STATUT_VALIDE);
            $this->csvDoc->save();
        }

        /**
         * IMPORT DEPUIS LE CSV
         */
         public function importCSV($withSave = true) {
             $this->importAnnexesFromCSV();

             $this->importMouvementsFromCSV();
             $this->importCrdsFromCSV();
             $this->drm->etape = ($this->fromEdi)? DRMClient::ETAPE_VALIDATION_EDI : DRMClient::ETAPE_VALIDATION;
             $this->drm->type_creation = DRMClient::DRM_CREATION_EDI;
             $this->drm->buildFavoris();
             $this->drm->storeDeclarant();
             $this->drm->initSociete();
             $this->updateAndControlCoheranceStocks();

             if($withSave) {
                 $this->drm->save();
             }
         }

         public function updateAndControlCoheranceStocks() {
             /*$stocks = array();
             foreach($this->drm->getProduitsDetails() as $detail) {
               $stocks[$detail->getHash()] = $detail->stocks_fin->final;
             }*/

             $this->drm->update();

             /*foreach($this->drm->getProduitsDetails() as $detail) {
                 if(!array_key_exists($detail->getHash(), $stocks) || is_null($stocks[$detail->getHash()])) {
                     continue;
                 }

                 if(round($stocks[$detail->getHash()], 2) == round($detail->stocks_fin->final, 2)) {
                     continue;
                 }
                 $this->csvDoc->addErreur($this->createError(1, sprintf("%s %0.2f hl (CSV) / %0.2f hl (calculé)", $detail->produit_libelle, $stocks[$detail->getHash()], $detail->stocks_fin->final), "Le stock fin de mois du CSV différent du calculé"));
             }*/

             if ($this->csvDoc->hasErreurs()) {
                 $this->csvDoc->setStatut(self::STATUT_WARNING);
                 $this->csvDoc->save();
             }
         }

        private function checkCSVIntegrity() {
            $ligne_num = 1;
            foreach ($this->getDocRows() as $csvRow) {
                if (($ligne_num == 1)
                && (KeyInflector::slugify($csvRow[self::CSV_TYPE]) != self::TYPE_CAVE)
                && (KeyInflector::slugify($csvRow[self::CSV_TYPE]) != self::TYPE_CRD)
                && (KeyInflector::slugify($csvRow[self::CSV_TYPE]) != self::TYPE_ANNEXE)) {
                    $ligne_num++;
                    continue;
                }
                if (!in_array(KeyInflector::slugify($csvRow[self::CSV_TYPE]), self::$permitted_types)) {
                    $this->csvDoc->addErreur($this->createWrongFormatTypeError($ligne_num, $csvRow));
                }
                if (!preg_match('/^[0-9]{6}$/', KeyInflector::slugify($csvRow[self::CSV_PERIODE]))) {
                    $this->csvDoc->addErreur($this->createWrongFormatPeriodeError($ligne_num, $csvRow));
                }
                if (!preg_match('/^[0-9]{8}$/', KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]))) {
                    $this->csvDoc->addErreur($this->createWrongNumeroCompteError($ligne_num, $csvRow));
                }
                if (!preg_match('/^FR[0-9A-Z]{11}$/', KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]))) {
                    $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
                }
                if($this->drm->getIdentifiant() != KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT])){
                  $this->csvDoc->addErreur($this->otherNumeroCompteError($ligne_num, $csvRow));
                }
                if($this->drm->getPeriode() != KeyInflector::slugify($csvRow[self::CSV_PERIODE])){
                  $this->csvDoc->addErreur($this->otherPeriodeError($ligne_num, $csvRow));
                }
                if($this->fromEdi && (!$this->drm || ! $this->drm->isCreationEdi())){
                  $this->csvDoc->addErreur($this->drmIsNotCreationEdiError($ligne_num, $csvRow));
                }

                $ligne_num++;
            }
        }

        private function checkImportMouvementsFromCSV() {
            return $this->importMouvementsFromCSV(true);
        }

        private function checkImportCrdsFromCSV() {
            return $this->importCrdsFromCSV(true);
        }

        private function checkImportAnnexesFromCSV() {
            return $this->importAnnexesFromCSV(true);
        }

        private function importMouvementsFromCSV($just_check = false) {
            $all_produits = $this->configuration->declaration->getProduits(date("Y-m-d"));

            $num_ligne = 1;
            $stocksDebutModifies = array();
            foreach ($this->getDocRows() as $csvRow) {
                if (KeyInflector::slugify($csvRow[self::CSV_TYPE] != self::TYPE_CAVE)) {
                    $num_ligne++;
                    continue;
                }
                $csvLibelleProductArray = $this->buildLibellesArrayWithRow($csvRow, true);
                $csvLibelleProductComplet = $this->slugifyProduitArrayOrString($csvLibelleProductArray);
                $founded_produit = false;

                foreach ($all_produits as $produit) {
                    if ($founded_produit) {
                        break;
                    }
                    $produitConfLibelleAOC = $this->slugifyProduitConf($produit);
                    $produitConfLibelleAOP = $this->slugifyProduitConf($produit,true);
                    $libelleCompletConfAOC = $this->slugifyProduitArrayOrString($produitConfLibelleAOC);
                    $libelleCompletConfAOP = $this->slugifyProduitArrayOrString($produitConfLibelleAOP);
                    $libelleCompletEnCsv = $this->slugifyProduitArrayOrString($csvRow[self::CSV_CAVE_LIBELLE_COMPLET]);
                    $isEmptyArray = $this->isEmptyArray($csvLibelleProductArray);
                    if ($isEmptyArray){
                      if(($libelleCompletConfAOC != $csvLibelleProductComplet) && ($libelleCompletConfAOP != $csvLibelleProductComplet)
                      && ($libelleCompletConfAOC != $libelleCompletEnCsv) && ($libelleCompletConfAOP != $libelleCompletEnCsv)
                      && ($this->slugifyProduitArrayOrString($produit->getLibelleFormat()) != $libelleCompletEnCsv)) {
                        continue;
                      }
                    }elseif((count(array_diff($csvLibelleProductArray, $produitConfLibelleAOC))) && (count(array_diff($csvLibelleProductArray, $produitConfLibelleAOP)))

                        && ($libelleCompletConfAOC != $csvLibelleProductComplet) && ($libelleCompletConfAOP != $csvLibelleProductComplet)
                        && ($libelleCompletConfAOC != $libelleCompletEnCsv) && ($libelleCompletConfAOP != $libelleCompletEnCsv)
                        && ($this->slugifyProduitArrayOrString($produit->getLibelleFormat()) != $libelleCompletEnCsv)) {
                        continue;
                    }elseif((count(array_diff($produitConfLibelleAOC, $csvLibelleProductArray))) && (count(array_diff($produitConfLibelleAOP, $csvLibelleProductArray)))
                        && ($libelleCompletConfAOC != $csvLibelleProductComplet) && ($libelleCompletConfAOP != $csvLibelleProductComplet)
                        && ($libelleCompletConfAOC != $libelleCompletEnCsv) && ($libelleCompletConfAOP != $libelleCompletEnCsv)
                        && ($this->slugifyProduitArrayOrString($produit->getLibelleFormat()) != $libelleCompletEnCsv)) {
                        continue;
                    }
                    $founded_produit = $produit;
                }
                if (!$founded_produit) {
                    $this->csvDoc->addErreur($this->productNotFoundError($num_ligne, $csvRow));
                    $num_ligne++;
                    continue;
                }

                $cat_mouvement = KeyInflector::slugify($csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT]);
                if(strtoupper(KeyInflector::slugify($cat_mouvement)) == self::COMPLEMENT){
                    $this->importComplementMvt($csvRow,$founded_produit,$just_check);
                    continue;
                }

                $type_mouvement = KeyInflector::slugify($csvRow[self::CSV_CAVE_TYPE_MOUVEMENT]);
                $detailNode = DRMClient::$types_node_from_libelles[strtoupper($csvRow[self::CSV_CAVE_TYPE_DRM])];
                if (!array_key_exists($cat_mouvement, $this->mouvements[$detailNode])) {
                    $this->csvDoc->addErreur($this->categorieMouvementNotFoundError($num_ligne, $csvRow));
                    $num_ligne++;
                    continue;
                }
                if (!array_key_exists($type_mouvement, $this->mouvements[$detailNode][$cat_mouvement])) {
                    $this->csvDoc->addErreur($this->typeMouvementNotFoundError($num_ligne, $csvRow));
                    $num_ligne++;
                    continue;
                }
                $confDetailMvt = $this->mouvements[$detailNode][$cat_mouvement][$type_mouvement];

                if (!$just_check) {
                    $drmDetails = $this->drm->addProduit($founded_produit->getHash(),DRMClient::$types_node_from_libelles[KeyInflector::slugify(strtoupper($csvRow[self::CSV_CAVE_TYPE_DRM]))]);

                    $detailTotalVol = $this->convertNumber($csvRow[self::CSV_CAVE_VOLUME]);
                    $volume = $this->convertNumber($csvRow[self::CSV_CAVE_VOLUME]);

                    $cat_key = $confDetailMvt->getParent()->getKey();
                    $type_key = $confDetailMvt->getKey();
                    if($cat_key == "stocks_debut" && !$drmDetails->canSetStockDebutMois()) {
                        continue;
                    }
                    if ($confDetailMvt->hasDetails()) {
                        $detailTotalVol += $this->convertNumber($drmDetails->getOrAdd($cat_key)->getOrAdd($type_key));

                        if ($type_key == 'export') {
                            $pays = $this->findPays($csvRow[self::CSV_CAVE_EXPORTPAYS]);
                            $detailNode = $drmDetails->getOrAdd($cat_key)->getOrAdd($type_key . '_details')->getOrAdd($pays,null);
                            if ($detailNode->volume) {
                                $volume+=$detailNode->volume;
                            }
                            $date = new DateTime($this->drm->getDate());
                            $detailNode->volume = $volume;
                            $detailNode->identifiant = $pays;
                            $detailNode->date_enlevement = $date->format('Y-m-d');
                        }
                        if ($type_key == 'vrac' || $type_key == 'contrat') {
                            $identifiantContrat = $this->findContratDocId($csvRow);
                            if(!$identifiantContrat){
                              continue;
                            }
                            $detailNode = $drmDetails->getOrAdd($cat_key)->getOrAdd($type_key . '_details')->getOrAdd($identifiantContrat,null);
                            if ($detailNode->volume) {
                                $volume+=$detailNode->volume;
                            }
                            $date = new DateTime($this->drm->getDate());
                            $detailNode->volume = $volume;
                            $detailNode->identifiant = $identifiantContrat;
                            $detailNode->date_enlevement = $date->format('Y-m-d');
                        }
                    } else {
                        $oldVolume = $drmDetails->getOrAdd($cat_key)->getOrAdd($type_key);
			
			if($cat_key == "stocks_debut" && !isset($stocksDebutModifies[$drmDetails->getHash()])) {
                            $oldVolume = 0;
                            $stocksDebutModifies[$drmDetails->getHash()] = true;
                        }

                        $drmDetails->getOrAdd($cat_key)->add($type_key, $oldVolume + $detailTotalVol);
                    }
                } else {
                    if ($confDetailMvt->hasDetails()) {
                        if ($confDetailMvt->getKey() == 'export') {
                            $pays = $this->findPays($csvRow[self::CSV_CAVE_EXPORTPAYS]);
                              if (!$pays) {
                                $this->csvDoc->addErreur($this->exportPaysNotFoundError($num_ligne, $csvRow));
                                $num_ligne++;
                                continue;
                            }
                            continue;
                        }
                        if ($confDetailMvt->getKey() == 'vrac' || $confDetailMvt->getKey() == 'contrat') {
                            if (!$csvRow[self::CSV_CAVE_CONTRATID]) {
                                $this->csvDoc->addErreur($this->contratIDNotFoundError($num_ligne, $csvRow));
                                $num_ligne++;
                                continue;
                            }
                            $vrac_id = $this->findContratDocId($csvRow);
                            if(!$vrac_id) {
                              $this->csvDoc->addErreur($this->contratIDNotFoundError($num_ligne, $csvRow));
                              $num_ligne++;
                              continue;
                            }
                        }
                        $vrac_id = $this->findContratDocId($csvRow);

                        if(!$vrac_id) {
                          $this->csvDoc->addErreur($this->contratIDNotFoundError($num_ligne, $csvRow));
                          $num_ligne++;
                          continue;
                        }
                    }
                }
                $num_ligne++;
            }
        }

        private function importComplementMvt($csvRow, $founded_produit, $just_check  = false){
              $type_complement = strtoupper(KeyInflector::slugify($csvRow[self::CSV_CAVE_TYPE_COMPLEMENT_PRODUIT]));
                if(!in_array($type_complement, self::$types_complement)){
                  $this->csvDoc->addErreur($this->typeComplementNotFoundError($num_ligne, $csvRow));
                  $num_ligne++;
                  continue;
                }
                $valeur_complement = $csvRow[self::CSV_CAVE_VALEUR_COMPLEMENT_PRODUIT];
                if(!$valeur_complement){
                  $this->csvDoc->addErreur($this->valueComplementVide($num_ligne, $csvRow));
                  $num_ligne++;
                  continue;
                }
                if(!$just_check){
                  $valeur_complement = $csvRow[self::CSV_CAVE_VALEUR_COMPLEMENT_PRODUIT];
                  $value = null;
                  switch ($type_complement) {
                    case self::COMPLEMENT_TAV:
                      $value = $this->convertNumber($valeur_complement);
                      break;
                    case self::COMPLEMENT_OBSERVATIONS:
                      $value = $valeur_complement;
                      break;
                    case self::COMPLEMENT_PREMIX:
                      $value = boolval($valeur_complement);
                      break;
                  }
                  $drmDetails = $this->drm->addProduit($founded_produit->getHash(),DRMClient::$types_node_from_libelles[KeyInflector::slugify(strtoupper($csvRow[self::CSV_CAVE_TYPE_DRM]))]);
                  $field = strtolower($type_complement);
                  $drmDetails->add($field, $value);
                }
        }

        private function importCrdsFromCSV($just_check = false) {
            $this->drm->remove('crds');
            $this->drm->add('crds');
            $num_ligne = 1;
            $etablissementObj = $this->drm->getEtablissementObject();

            $all_contenances_origine = sfConfig::get('app_vrac_contenances');
            $all_contenances = array();
            foreach ($all_contenances_origine as $contenance_key => $contenance) {
              $newKey = strtoupper(str_replace(" ","",str_replace(",",".",$contenance_key)));
              $all_contenances[$newKey] = $contenance;
            }
            foreach ($this->getDocRows() as $csvRow) {
                if (KeyInflector::slugify($csvRow[self::CSV_TYPE] != self::TYPE_CRD)) {
                    $num_ligne++;
                    continue;
                }
                $crd_regime = "";
                $crd_regime_libelle = KeyInflector::slugify($csvRow[self::CSV_CRD_REGIME]);
                if(array_key_exists($crd_regime_libelle,self::$regimes_crd)){
                  $crd_regime = self::$regimes_crd[$crd_regime_libelle];
                }
                if(!$crd_regime){
                  $crd_regime = ($etablissementObj->exist('crd_regime'))? $etablissementObj->get('crd_regime') : EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU;
                }

                $genre = KeyInflector::slugify($csvRow[self::CSV_CRD_GENRE]);
                $couleur = KeyInflector::slugify($csvRow[self::CSV_CRD_COULEUR]);
                $litrageLibelle = strtoupper(str_replace(" ","",str_replace(",",".",$csvRow[self::CSV_CRD_CENTILITRAGE])));
                $categorie_key = $csvRow[self::CSV_CRD_CATEGORIE_KEY];
                $type_key = $csvRow[self::CSV_CRD_TYPE_KEY];
                $quantite = KeyInflector::slugify($csvRow[self::CSV_CRD_QUANTITE]);
                if($categorie_key == "stocks_debut"){ $categorie_key = 'stock_debut'; }
                if($categorie_key == "stocks_fin"){ $categorie_key = 'stock_fin'; }
                $fieldNameCrd = $categorie_key;

                if ($categorie_key != "stock_debut" && $categorie_key != "stock_fin") {
                    $fieldNameCrd.="_" . $type_key;
                }
                if ($just_check) {
                    if(!array_key_exists($litrageLibelle,$all_contenances)){
                      $this->csvDoc->addErreur($this->crdContenanceWrongFormatError($num_ligne, $csvRow));
                    }
                    if(!in_array($categorie_key,self::$cat_crd_mvts)){
                      $this->csvDoc->addErreur($this->crdCatWrongFormatError($num_ligne, $csvRow));
                    }
                    if(!in_array($type_key,self::$type_crd_mvts)){
                      $this->csvDoc->addErreur($this->crdTypeWrongFormatError($num_ligne, $csvRow));
                    }
                    $num_ligne++;
                } else {
                    if(!array_key_exists($litrageLibelle,$all_contenances)){ continue; }

                    $centilitrage = $all_contenances[$litrageLibelle] * 100000;
                    $regimeNode = $this->drm->getOrAdd('crds')->getOrAdd($crd_regime);

                    $keyNode = $regimeNode->constructKey($genre, $couleur, $centilitrage, $litrageLibelle);
                    if (!$regimeNode->exist($keyNode)) {
                        $litrageLibelle = $csvRow[self::CSV_CRD_CENTILITRAGE];
                        $regimeNode->getOrAddCrdNode($genre, $couleur, $centilitrage, $litrageLibelle);
                    }
                    $regimeNode->getOrAdd($keyNode)->$fieldNameCrd = intval($quantite);
                    $num_ligne++;
                }
            }
        }

        private function importAnnexesFromCSV($just_check = false) {
            $num_ligne = 1;
            $typesAnnexes = array_keys($this->type_annexes);
            foreach ($this->getDocRows() as $csvRow) {
                if (KeyInflector::slugify($csvRow[self::CSV_TYPE] != self::TYPE_ANNEXE)) {
                    $num_ligne++;
                    continue;
                }
                switch (KeyInflector::slugify($csvRow[self::CSV_ANNEXE_TYPEANNEXE])) {
                    case self::TYPE_ANNEXE_NONAPUREMENT:
                        $numero_document = KeyInflector::slugify($csvRow[self::CSV_ANNEXE_NUMERODOCUMENT]);
                        $date_emission = KeyInflector::slugify($csvRow[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION]);
                        $dt = DateTime::createFromFormat("d-m-Y", $date_emission);

                        $numero_accise = KeyInflector::slugify($csvRow[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST]);
                        if (!$numero_document) {
                            if ($just_check) {
                                $this->csvDoc->addErreur($this->annexesNumeroDocumentError($num_ligne, $csvRow));
                            }
                            $num_ligne++;
                            break;
                        }
                        if (!$date_emission || $dt == false || array_sum($dt->getLastErrors())) {
                            if ($just_check) {
                                $this->csvDoc->addErreur($this->annexesNonApurementWrongDateError($num_ligne, $csvRow));
                            }
                            $num_ligne++;
                            break;
                        }
                        if (!preg_match('/^[A-Z]{2}[0-9A-Z]{11}$/', $numero_accise)) {
                            if ($just_check) {
                                $this->csvDoc->addErreur($this->annexesNonApurementWrongNumAcciseError($num_ligne, $csvRow));
                            }
                            $num_ligne++;
                            break;
                        }
                        if (!$just_check) {
                            $nonAppurementNode = $this->drm->getOrAdd('releve_non_apurement')->getOrAdd($numero_document);
                            $nonAppurementNode->numero_document = $numero_document;
                            $nonAppurementNode->date_emission = $dt->format('Y-m-d');
                            $nonAppurementNode->numero_accise = $numero_accise;
                        }
                        $num_ligne++;
                        break;

                    case self::TYPE_ANNEXE_OBSERVATIONS:
                        $observations = KeyInflector::slugify($csvRow[self::CSV_ANNEXE_OBSERVATION]);
                        if (!$observations && $just_check) {
                            $this->csvDoc->addErreur($this->observationsEmptyError($num_ligne, $csvRow));
                        }
                        if (!$just_check) {
                            $this->drm->add('observations', $observations);
                        }
                        $num_ligne++;
                        break;


                    case self::TYPE_ANNEXE_SUCRE:
                        $quantite_sucre = str_replace(',', '.', $csvRow[self::CSV_ANNEXE_QUANTITE]);
                        if (!$quantite_sucre || !is_numeric($quantite_sucre)) {
                            if ($just_check) {
                                $this->csvDoc->addErreur($this->sucreWrongFormatError($num_ligne, $csvRow));
                            }
                            $num_ligne++;
                            break;
                        }
                        if (!$just_check) {
                            $this->drm->add('quantite_sucre', $quantite_sucre);
                        }
                        $num_ligne++;
                        break;

                    case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADAC:
                    case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DSADSAC:
                    case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_EMPREINTE:
                        $docTypeAnnexe = $this->drm->getOrAdd('documents_annexes')->getOrAdd(KeyInflector::slugify($csvRow[self::CSV_ANNEXE_TYPEANNEXE]));
                        $annexeTypeMvt = KeyInflector::slugify($csvRow[self::CSV_ANNEXE_TYPEMVT]);
                        $numDocument = KeyInflector::slugify($csvRow[self::CSV_ANNEXE_NUMERODOCUMENT]);
                        if (!in_array($annexeTypeMvt, self::$permitted_annexes_type_mouvements)) {
                            if ($just_check) {
                                $this->csvDoc->addErreur($this->annexesTypeMvtWrongFormatError($num_ligne, $csvRow));
                            } $num_ligne++;
                            break;
                        }
                        if (!$numDocument) {
                            if ($just_check) {
                                $this->csvDoc->addErreur($this->annexesNumeroDocumentError($num_ligne, $csvRow));
                            } $num_ligne++;
                            break;
                        }
                        if (!$just_check) {
                            $docTypeAnnexe->add(strtolower($annexeTypeMvt), $numDocument);
                        }
                        $num_ligne++;
                        break;
                    case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_STATS_EUROPEENNES:
                        $type = strtolower($csvRow[self::CSV_ANNEXE_TYPEMVT]);
                        $this->drm->declaratif->statistiques->add($type,$this->convertNumber($csvRow[self::CSV_CAVE_VOLUME]));
                    break;
                    default:
                        if ($just_check) {
                            $this->csvDoc->addErreur($this->typeDocumentWrongFormatError($num_ligne, $csvRow));
                        }
                        $num_ligne++;
                        break;
                }
            }
        }

        private function convertNumber($number){
          $numberPointed = trim(str_replace(",",".",$number));
          return floatval($numberPointed);
        }

        /**
         * Functions de création d'erreurs
         */

        private function createWrongFormatTypeError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      KeyInflector::slugify($csvRow[self::CSV_TYPE]),
                                      "Choix possible type : " . implode(', ', self::$permitted_types),
                                      CSVClient::LEVEL_WARNING);
        }

        private function createWrongNumeroCompteError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]),
                                      "Le numéro de compte est mal formatté : il doit être au format 12345601",
                                      CSVClient::LEVEL_ERROR);
        }

        private function otherNumeroCompteError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANT]),
                                      "Le numéro de compte n'est pas celui du ressortissant attendu",
                                      CSVClient::LEVEL_ERROR);
        }


        private function createWrongFormatPeriodeError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      KeyInflector::slugify($csvRow[self::CSV_PERIODE]),
                                      "Format période : AAAAMM",
                                      CSVClient::LEVEL_ERROR);
        }

        private function otherPeriodeError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      KeyInflector::slugify($csvRow[self::CSV_PERIODE]),
                                      "La période spécifiée ne correspond pas à celle transmise",
                                      CSVClient::LEVEL_ERROR);
        }

        private function drmIsNotCreationEdiError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      'DRM existante',
                                      "Une DRM sur cette période existe déjà",
                                      CSVClient::LEVEL_ERROR);
        }


        private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]),
                                      "Format numéro d'accise : FRXXXXXXXXXXX",
                                      CSVClient::LEVEL_WARNING);
        }

        private function productNotFoundError($num_ligne, $csvRow) {
            $libellesArray = $this->buildLibellesArrayWithRow($csvRow);
            $libelles = ($this->isEmptyArray($libellesArray))? $csvRow[self::CSV_CAVE_LIBELLE_COMPLET] : implode(' ', $libellesArray);
            return $this->createError($num_ligne,
                                      $libelles,
                                      "Le produit n'a pas été trouvé",
                                      CSVClient::LEVEL_WARNING);
        }

        private function categorieMouvementNotFoundError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT],
                                      "Le catégorie de mouvement n'a pas été trouvé",
                                      CSVClient::LEVEL_WARNING);
        }

        private function typeMouvementNotFoundError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT],
                                      "Le type de mouvement n'a pas été trouvé",
                                      CSVClient::LEVEL_WARNING);
        }

        private function typeComplementNotFoundError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CAVE_TYPE_COMPLEMENT_PRODUIT],
                                      "Le type de complément doit être observations, tav ou premix",
                                      CSVClient::LEVEL_WARNING);
        }

        private function valueComplementVide($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CAVE_VALEUR_COMPLEMENT_PRODUIT],
                                      "La valeur du complément doit être renseignée",
                                      CSVClient::LEVEL_WARNING);
        }

        private function exportPaysNotFoundError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CAVE_EXPORTPAYS],
                                      "Le pays d'export n'a pas été trouvé",
                                      CSVClient::LEVEL_WARNING);
        }

        private function contratIDNotFoundError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CAVE_CONTRATID],
                                      "L'id du contrat ne peut est vide ou n'a pas été trouvé",
                                      CSVClient::LEVEL_WARNING);
        }

        private function observationsEmptyError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      "Observations",
                                      "Les observations sont vides.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function sucreWrongFormatError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_ANNEXE_QUANTITE],
                                      "La quantité de sucre est nulle ou possède un mauvais format.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function typeDocumentWrongFormatError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_ANNEXE_TYPEANNEXE],
                                      "Le type de document d'annexe n'est pas connu.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function crdContenanceWrongFormatError ($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CRD_CENTILITRAGE],
                                      "La contenance de ces CRD n'est pas reconnu.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function crdCatWrongFormatError ($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CRD_CATEGORIE_KEY],
                                      "La catégorie de ces CRD n'est pas reconnu.",
                                      CSVClient::LEVEL_WARNING);
        }


        private function crdTypeWrongFormatError ($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_CRD_TYPE_KEY],
                                      "Le type de ces CRD n'est pas reconnu.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function annexesTypeMvtWrongFormatError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_ANNEXE_TYPEMVT],
                                      "Le type d'enregistrement des " . $csvRow[self::CSV_ANNEXE_TYPEANNEXE] . " doit être 'début' ou 'fin' .",
                                      CSVClient::LEVEL_WARNING);
        }

        private function annexesNumeroDocumentError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_ANNEXE_TYPEANNEXE],
                                      "Le numéro de document ne peut pas être vide.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function annexesNonApurementWrongDateError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION],
                                      "La date est vide ou mal formattée.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function annexesNonApurementWrongNumAcciseError($num_ligne, $csvRow) {
            return $this->createError($num_ligne,
                                      $csvRow[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST], "La numéro d'accise du destinataire est vide ou mal formatté.",
                                      CSVClient::LEVEL_WARNING);
        }

        private function createError($num_ligne, $erreur_csv, $raison, $level = null) {
            $error = new stdClass();
            $error->num_ligne = $num_ligne;
            $error->erreur_csv = $erreur_csv;
            $error->raison = $raison;
            $error->level = $level;
            return $error;

        }

        private function findContratDocId($csvRow) {
          if($vrac = VracClient::getInstance()->findByNumContrat("VRAC-".KeyInflector::slugify($csvRow[self::CSV_CAVE_CONTRATID]), acCouchdbClient::HYDRATE_JSON)) {
              return $vrac->_id;
          }

          return VracClient::getInstance()->findDocIdByNumArchive($this->drm->campagne, $csvRow[self::CSV_CAVE_CONTRATID], 2);
        }

        /**
         * Fin des functions de création d'erreurs
         */
        private function buildLibellesArrayWithRow($csvRow, $with_slugify = false) {
            $certification = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_CERTIFICATION]) : $csvRow[self::CSV_CAVE_CERTIFICATION];
            $genre = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_GENRE]) : $csvRow[self::CSV_CAVE_GENRE];
            $this->uniformisationGenre($genre);
            $appellation = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_APPELLATION]) : $csvRow[self::CSV_CAVE_APPELLATION];
            $mention = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_MENTION]) : $csvRow[self::CSV_CAVE_MENTION];
            $lieu = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_LIEU]) : $csvRow[self::CSV_CAVE_LIEU];
            $couleur = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_COULEUR]) : $csvRow[self::CSV_CAVE_COULEUR];
            $cepage = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_CEPAGE]) : $csvRow[self::CSV_CAVE_CEPAGE];

            $libelles = array(strtoupper($certification),
                strtoupper($genre),
                strtoupper($appellation),
                strtoupper($mention),
                strtoupper($lieu),
                strtoupper($couleur),
                strtoupper($cepage));
            foreach ($libelles as $key => $libelle) {
                if (!$libelle) {
                    $libelles[$key] = null;
                }
            }
            return $libelles;
        }

        private function uniformisationGenre(&$genre){
          $gs = self::$genres_synonyme;
          $gslug = $this->slugifyProduitArrayOrString($genre);
          if(array_key_exists($gslug,$gs)){
            $genre = $this->slugifyProduitArrayOrString($gs[$gslug]);
          }
        }

        private function slugifyProduitArrayOrString($produitLibelles) {
          $produitLibellesStr = is_array($produitLibelles)? implode(" ",$produitLibelles) : $produitLibelles;
          return strtoupper(KeyInflector::slugify(trim(preg_replace("/[\ ]+/"," ",$produitLibellesStr))));
        }

        private function slugifyProduitConf($produit, $withAOP = false, $withGenre = true) {
            $libellesSlugified = array();
            foreach ($produit->getLibelles() as $key => $libelle) {
                $libellesSlugified[] = strtoupper(KeyInflector::slugify($libelle));
            }
            $genreKey = $produit->getGenre()->getKey();
            $genreLibelle = self::$genres[$genreKey];
            $libellesSlugified[1] = strtoupper(KeyInflector::slugify($genreLibelle));
            if(($libellesSlugified[0] == "AOC") && $withAOP){
                $libellesSlugified[0]="AOP";
            }
            foreach ($libellesSlugified as $key => $libelle) {
                if (!$libelle) {
                    $libellesSlugified[$key] = null;
                }
            }
            return $libellesSlugified;
        }

        private function buildAllMouvements() {
            $all_conf_details_slugified = array();
            foreach (DRMClient::$types_node_from_libelles as $detailsNode) {
              if($this->configuration->declaration->exist($detailsNode)){
                if (!array_key_exists($detailsNode, $all_conf_details_slugified)) {
                      $all_conf_details_slugified[$detailsNode] = array();
                }
                foreach ($this->configuration->declaration->get($detailsNode)->getAllDetails() as $all_conf_detail_cat_Key => $all_conf_detail_cat) {
                  foreach ($all_conf_detail_cat as $key_type => $type_detail) {
                    if (!array_key_exists(KeyInflector::slugify($all_conf_detail_cat_Key), $all_conf_details_slugified[$detailsNode])) {
                      $all_conf_details_slugified[$detailsNode][KeyInflector::slugify($all_conf_detail_cat_Key)] = array();
                    }
                    if (KeyInflector::slugify($key_type) == 'VRAC') {
                      $all_conf_details_slugified[$detailsNode][KeyInflector::slugify($all_conf_detail_cat_Key)]['CONTRAT'] = $type_detail;
                    }
                    $all_conf_details_slugified[$detailsNode][KeyInflector::slugify($all_conf_detail_cat_Key)][KeyInflector::slugify($key_type)] = $type_detail;
                  }
                }
              }
            }
            return $all_conf_details_slugified;
        }

        private function isEmptyArray($array){
          foreach ($array as $csvLibelle) {
            if($csvLibelle){
              return false;
            }
          }
            return true;
        }


    }
