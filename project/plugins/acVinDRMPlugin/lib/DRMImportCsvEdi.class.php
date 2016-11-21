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

    private $configuration = null;
    private $mouvements = array();

    public function __construct($file, DRM $drm = null) {
        $this->csvDoc = CSVClient::getInstance()->createOrFindDocFromDRM($file, $drm);
        $this->configuration = ConfigurationClient::getCurrent();
        $this->mouvements = $this->buildAllMouvements();
        parent::__construct($file, $drm);
    }

    private function getDocRows() {
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
    public function importCSV() {
        $this->importAnnexesFromCSV();

        $this->importMouvementsFromCSV();
        $this->importCrdsFromCSV();
        $this->drm->teledeclare = true;
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->type_creation = DRMClient::DRM_CREATION_EDI;
        $this->drm->buildFavoris();
        $this->drm->storeDeclarant();
        $this->drm->initSociete();
        $this->drm->update();
        $this->drm->save();
    }

    private function checkCSVIntegrity() {
        $ligne_num = 1;
        foreach ($this->getDocRows() as $csvRow) {
            if ($ligne_num == 1 && KeyInflector::slugify($csvRow[self::CSV_TYPE]) == 'TYPE') {
                $ligne_num++;
                continue;
            }
            if (!in_array(KeyInflector::slugify($csvRow[self::CSV_TYPE]), self::$permitted_types)) {
                $this->csvDoc->addErreur($this->createWrongFormatTypeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^[0-9]{6}$/', KeyInflector::slugify($csvRow[self::CSV_PERIODE]))) {
                $this->csvDoc->addErreur($this->createWrongFormatPeriodeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^FR[0-9]{11}$/', KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]))) {
                $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
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
        $all_produits = $this->configuration->declaration->getProduitsAll();

        $num_ligne = 1;
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

                if ((count(array_diff($csvLibelleProductArray, $produitConfLibelleAOC))) && (count(array_diff($csvLibelleProductArray, $produitConfLibelleAOP)))
                    && ($libelleCompletConfAOC != $csvLibelleProductComplet) && ($libelleCompletConfAOP != $csvLibelleProductComplet)
                    && ($libelleCompletConfAOC != $libelleCompletEnCsv) && ($libelleCompletConfAOP != $libelleCompletEnCsv)) {
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
            $type_mouvement = KeyInflector::slugify($csvRow[self::CSV_CAVE_TYPE_MOUVEMENT]);
            if (!array_key_exists($cat_mouvement, $this->mouvements)) {
                $this->csvDoc->addErreur($this->categorieMouvementNotFoundError($num_ligne, $csvRow));
                $num_ligne++;
                continue;
            }
            if (!array_key_exists($type_mouvement, $this->mouvements[$cat_mouvement])) {
                $this->csvDoc->addErreur($this->typeMouvementNotFoundError($num_ligne, $csvRow));
                $num_ligne++;
                continue;
            }
            $confDetailMvt = $this->mouvements[$cat_mouvement][$type_mouvement];

            if (!$just_check) {
                $drmDetails = $this->drm->addProduit($founded_produit->getHash());

                $detailTotalVol = $this->convertNumber($csvRow[self::CSV_CAVE_VOLUME]);
                $volume = $this->convertNumber($csvRow[self::CSV_CAVE_VOLUME]);

                $cat_key = $confDetailMvt->getParent()->getKey();
                $type_key = $confDetailMvt->getKey();
                if ($confDetailMvt->hasDetails()) {
                    $detailTotalVol += $this->convertNumber($drmDetails->getOrAdd($cat_key)->getOrAdd($type_key));

                    if ($type_key == 'export') {
                        $pays = array_search($csvRow[self::CSV_CAVE_EXPORTPAYS], $this->countryList);
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
                        $identifiantContrat = "VRAC-".KeyInflector::slugify($csvRow[self::CSV_CAVE_CONTRATID]);
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
                    $drmDetails->getOrAdd($cat_key)->add($type_key, $oldVolume + $detailTotalVol);
                }
            } else {
                if ($confDetailMvt->hasDetails()) {
                    if ($confDetailMvt->getKey() == 'export') {
                        if (!array_search($csvRow[self::CSV_CAVE_EXPORTPAYS], $this->countryList)) {
                            $this->csvDoc->addErreur($this->exportPaysNotFoundError($num_ligne, $csvRow));
                            $num_ligne++;
                            continue;
                        }
                    }
                    if ($confDetailMvt->getKey() == 'vrac' || $confDetailMvt->getKey() == 'contrat') {
                        if (!$csvRow[self::CSV_CAVE_CONTRATID]) {
                            $this->csvDoc->addErreur($this->contratIDNotFoundError($num_ligne, $csvRow));
                            $num_ligne++;
                            continue;
                        }
                    }
                }
            }
            $num_ligne++;
        }
    }

    private function importCrdsFromCSV($just_check = false) {
        $num_ligne = 1;
        $etablissementObj = $this->drm->getEtablissementObject();

        $crd_regime = ($etablissementObj->exist('crd_regime'))? $etablissementObj->get('crd_regime') : EtablissementClient::REGIME_CRD_COLLECTIF_SUSPENDU;
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
            $genre = KeyInflector::slugify($csvRow[self::CSV_CRD_GENRE]);
            $couleur = KeyInflector::slugify($csvRow[self::CSV_CRD_COULEUR]);
            $litrageLibelle = strtoupper(str_replace(" ","",str_replace(",",".",$csvRow[self::CSV_CRD_CENTILITRAGE])));
            $categorie_key = $csvRow[self::CSV_CRD_CATEGORIE_KEY];
            $type_key = $csvRow[self::CSV_CRD_TYPE_KEY];
            $quantite = KeyInflector::slugify($csvRow[self::CSV_CRD_QUANTITE]);
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
            } else {

                $centilitrage = $all_contenances[$litrageLibelle] * 100000;
                $regimeNode = $this->drm->getOrAdd('crds')->getOrAdd($crd_regime);
                $keyNode = $regimeNode->constructKey($genre, $couleur, $centilitrage);
                if (!$regimeNode->exist($keyNode)) {
                    $regimeNode->getOrAddCrdNode($genre, $couleur, $centilitrage);
                }
                try{
                  $regimeNode->getOrAdd($keyNode)->$fieldNameCrd = intval($quantite);
                }catch(sfException $e){
                  //  var_dump($keyNode,$fieldNameCrd,$quantite); exit;
                }
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
                    if (!preg_match('/^FR0[0-9]{10}$/', $numero_accise)) {
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
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_TYPE]), "Choix possible type : " . implode(', ', self::$permitted_types));
    }

    private function createWrongFormatPeriodeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_PERIODE]), "Format période : AAAAMM");
    }

    private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]), "Format numéro d'accise : FRXXXXXXXXXXX");
    }

    private function productNotFoundError($num_ligne, $csvRow) {
        $libellesArray = $this->buildLibellesArrayWithRow($csvRow);
        return $this->createError($num_ligne, implode(' ', $libellesArray), "Le produit n'a pas été trouvé");
    }

    private function categorieMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT], "Le catégorie de mouvement n'a pas été trouvé");
    }

    private function typeMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT], "Le type de mouvement n'a pas été trouvé");
    }

    private function exportPaysNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_EXPORTPAYS], "Le pays d'export n'a pas été trouvé");
    }

    private function contratIDNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "L'id du contrat ne peut pas être vide");
    }

    private function observationsEmptyError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, "Observations", "Les observations sont vides.");
    }

    private function sucreWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La quantité de sucre est nulle ou possède un mauvais format.");
    }

    private function typeDocumentWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le type de document d'annexe n'est pas connu.");
    }

    private function crdContenanceWrongFormatError ($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CRD_CENTILITRAGE], "La contenance de ces CRD n'est pas reconnu.");
    }

    private function crdCatWrongFormatError ($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CRD_CATEGORIE_KEY], "La catégorie de ces CRD n'est pas reconnu.");
    }

    private function crdTypeWrongFormatError ($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CRD_TYPE_KEY], "Le type de ces CRD n'est pas reconnu.");
    }

    private function annexesTypeMvtWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le type d'enregistrement des " . $csvRow[self::CSV_ANNEXE_TYPEANNEXE] . " doit être 'début' ou 'fin' .");
    }

    private function annexesNumeroDocumentError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le numéro de document ne peut pas être vide.");
    }

    private function annexesNonApurementWrongDateError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION], "La date est vide ou mal formattée.");
    }

    private function annexesNonApurementWrongNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST], "La numéro d'accise du destinataire est vide ou mal formatté.");
    }

    private function createError($num_ligne, $erreur_csv, $raison) {
        $error = new stdClass();
        $error->num_ligne = $num_ligne;
        $error->erreur_csv = $erreur_csv;
        $error->raison = $raison;
        return $error;
    }

    /**
     * Fin des functions de création d'erreurs
     */
    private function buildLibellesArrayWithRow($csvRow, $with_slugify = false) {
        $certification = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_CERTIFICATION]) : $csvRow[self::CSV_CAVE_CERTIFICATION];
        $genre = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_GENRE]) : $csvRow[self::CSV_CAVE_GENRE];
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
        $all_conf_details = $this->configuration->declaration->getDetailConfiguration()->getAllDetails();
        $all_conf_details_slugified = array();
        foreach ($all_conf_details as $all_conf_detail_cat_Key => $all_conf_detail_cat) {
            foreach ($all_conf_detail_cat as $key_type => $type_detail) {
                if (!array_key_exists(KeyInflector::slugify($all_conf_detail_cat_Key), $all_conf_details_slugified)) {
                    $all_conf_details_slugified[KeyInflector::slugify($all_conf_detail_cat_Key)] = array();
                }
                if (KeyInflector::slugify($key_type) == 'VRAC') {
                    $all_conf_details_slugified[KeyInflector::slugify($all_conf_detail_cat_Key)]['CONTRAT'] = $type_detail;
                }
                $all_conf_details_slugified[KeyInflector::slugify($all_conf_detail_cat_Key)][KeyInflector::slugify($key_type)] = $type_detail;
            }
        }
        return $all_conf_details_slugified;
    }

}
