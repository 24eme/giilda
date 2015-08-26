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

    public function __construct($file, DRM $drm = null) {
        $this->csvDoc = CSVClient::getInstance()->createOrFindDocFromDRM($file, $drm);
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
        $this->checkImportMouvementsFromCSV();
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
        $this->importMouvementsFromCSV();
        $this->importCrdsFromCSV();
        $this->importAnnexesFromCSV();
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
            if ($ligne_num == 1 && $csvRow[self::CSV_TYPE] == 'TYPE') {
                $ligne_num++;
                continue;
            }
            if (!in_array($csvRow[self::CSV_TYPE], self::$permitted_types)) {
                $this->csvDoc->addErreur($this->createWrongFormatTypeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^[0-9]{6}$/', $csvRow[self::CSV_PERIODE])) {
                $this->csvDoc->addErreur($this->createWrongFormatPeriodeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^FR0[0-9]{10}$/', $csvRow[self::CSV_NUMACCISE])) {
                $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
            }
            $ligne_num++;
        }
    }

    private function checkImportMouvementsFromCSV() {
        return $this->importMouvementsFromCSV(true);
    }

    private function checkImportCrdsFromCSV($csv, &$erreur_array) {
        foreach ($csv->getCsv() as $csvRow) {
            
        }
    }

    private function checkImportAnnexesFromCSV($csv, &$erreur_array) {
        foreach ($csv->getCsv() as $csvRow) {
            
        }
    }

    private function importMouvementsFromCSV($just_check = false) {
        $configuration = ConfigurationClient::getCurrent();
        $all_produits = $configuration->declaration->getProduitsAll();

        $num_ligne = 1;
        foreach ($this->getDocRows() as $csvRow) {
            if ($csvRow[self::CSV_TYPE] != self::TYPE_CAVE) {
                $num_ligne++;
                continue;
            }
            $csvLibelleProductArray = $this->buildLibellesArrayWithRow($csvRow);
            $founded_produit = false;

            foreach ($all_produits as $produit) {
                if ($founded_produit) {
                    break;
                }
                if (count(array_diff($csvLibelleProductArray, $produit->getLibelles()))) {
                    continue;
                }
                $founded_produit = $produit;
            }
            if (!$founded_produit) {
                $this->csvDoc->addErreur($this->productNotFoundError($num_ligne, $csvRow));
                $num_ligne++;
                continue;
            }

            $detailsConfiguration = $configuration->declaration->getDetailConfiguration();
            $cat_mouvement = $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT];
            $type_mouvement = $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT];
            if (!$detailsConfiguration->exist($cat_mouvement)) {
                $this->csvDoc->addErreur($this->categorieMouvementNotFoundError($num_ligne, $csvRow));
                $num_ligne++;
                continue;
            }
            if (!$detailsConfiguration->get($cat_mouvement)->exist($type_mouvement)) {
                $this->csvDoc->addErreur($this->typeMouvementNotFoundError($num_ligne, $csvRow));
                $num_ligne++;
                continue;
            }
            if (!$just_check) {
                $drmDetails = $this->drm->addProduit($founded_produit->getHash());
                $hasDetails = $detailsConfiguration->get($cat_mouvement)->get($type_mouvement)->hasDetails();
                $detailTotalVol = floatval($csvRow[self::CSV_CAVE_VOLUME]);
                $volume = floatval($csvRow[self::CSV_CAVE_VOLUME]);
                if ($hasDetails) {
                    $detailTotalVol += floatval($drmDetails->getOrAdd($cat_mouvement)->getOrAdd($type_mouvement));
                    $detailNode = $drmDetails->getOrAdd($cat_mouvement)->getOrAdd($type_mouvement . '_details')->getOrAdd($csvRow[self::CSV_CAVE_COMPLEMENT]);
                    if ($detailNode->volume) {
                        $volume+=$detailNode->volume;
                    }
                    $date = new DateTime($this->drm->getDate());
                    $detailNode->volume = $volume;
                    $detailNode->identifiant = $csvRow[self::CSV_CAVE_COMPLEMENT];
                    $detailNode->date_enlevement = $date->format('Y-m-d');
                }
                $drmDetails->getOrAdd($cat_mouvement)->add($type_mouvement, $detailTotalVol);
            }
            $num_ligne++;
        }
    }

    private function importCrdsFromCSV() {
        $num_ligne = 1;
        $crd_regime = $this->drm->getEtablissementObject()->get('crd_regime');
        $all_contenances = sfConfig::get('app_vrac_contenances');
        foreach ($this->getDocRows() as $csvRow) {
            if ($csvRow[self::CSV_TYPE] != self::TYPE_CRD) {
                $num_ligne++;
                continue;
            }
            $genre = $csvRow[self::CSV_CRD_GENRE];
            $couleur = $csvRow[self::CSV_CRD_COULEUR];
            $litrageLibelle = $csvRow[self::CSV_CRD_CENTILITRAGE];
            $quantite_key = $csvRow[self::CSV_CRD_QUANTITE_KEY];
            $quantite = $csvRow[self::CSV_CRD_QUANTITE];

            $centilitrage = $all_contenances[$litrageLibelle] * 100000;
            $regimeNode = $this->drm->getOrAdd('crds')->getOrAdd($crd_regime);
            $keyNode = $regimeNode->constructKey($genre, $couleur, $centilitrage);
            if (!$regimeNode->exist($keyNode)) {
                $regimeNode->getOrAddCrdNode($genre, $couleur, $centilitrage);
            }
            $regimeNode->getOrAdd($keyNode)->$quantite_key = intval($quantite);
            $num_ligne++;
        }
    }

    private function importAnnexesFromCSV() {
        $num_ligne = 1;
        $typesAnnexes = array_keys($this->type_annexes);
        foreach ($this->getDocRows() as $csvRow) {
            if ($csvRow[self::CSV_TYPE] != self::TYPE_ANNEXE) {
                $num_ligne++;
                continue;
            }
            switch ($csvRow[self::CSV_ANNEXE_TYPEANNEXE]) {
                case self::TYPE_ANNEXE_NONAPUREMENT:
                    $numero_document = $csvRow[self::CSV_ANNEXE_IDDOC];
                    $date_emission = $csvRow[self::CSV_ANNEXE_COMPLEMENT];
                    $numero_accise = $csvRow[self::CSV_ANNEXE_TYPEMVT_ACCISE];
                    $nonAppurementNode = $this->drm->getOrAdd('releve_non_apurement')->getOrAdd($numero_document);
                    $nonAppurementNode->numero_document = $numero_document;
                    $nonAppurementNode->date_emission = $date_emission;
                    $nonAppurementNode->numero_accise = $numero_accise;
                    $num_ligne++;
                    break;

                case self::TYPE_ANNEXE_OBSERVATIONS:
                    $this->drm->add('observations', $csvRow[self::CSV_ANNEXE_COMPLEMENT]);
                    $num_ligne++;
                    break;


                case self::TYPE_ANNEXE_SUCRE:
                    $this->drm->add('quantite_sucre', $csvRow[self::CSV_ANNEXE_QUANTITE]);
                    $num_ligne++;
                    break;

                case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DAADAC:
                case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_DSADSAC:
                case DRMClient::DRM_DOCUMENTACCOMPAGNEMENT_EMPREINTE:
                    $docTypeAnnexe = $this->drm->getOrAdd('documents_annexes')->getOrAdd($csvRow[self::CSV_ANNEXE_TYPEANNEXE]);
                    $docTypeAnnexe->add($csvRow[self::CSV_ANNEXE_TYPEMVT_ACCISE], $csvRow[self::CSV_ANNEXE_COMPLEMENT]);
                    $num_ligne++;
                    break;
            }
        }
    }

    /**
     * Functions de création d'erreurs
     */
    private function createWrongFormatTypeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_TYPE], "Choix possible type : " . implode(', ', self::$permitted_types));
    }

    private function createWrongFormatPeriodeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_PERIODE], "Format période : AAAAMM");
    }

    private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_NUMACCISE], "Format numéro d'accise : FR0XXXXXXXXXX");
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
    private function buildLibellesArrayWithRow($csvRow) {
        $libelles = array($csvRow[self::CSV_CAVE_CERTIFICATION],
            $csvRow[self::CSV_CAVE_GENRE],
            $csvRow[self::CSV_CAVE_APPELLATION],
            $csvRow[self::CSV_CAVE_MENTION],
            $csvRow[self::CSV_CAVE_LIEU],
            $csvRow[self::CSV_CAVE_COULEUR],
            $csvRow[self::CSV_CAVE_CEPAGE]);
        foreach ($libelles as $key => $libelle) {
            if (!$libelle) {
                $libelles[$key] = null;
            }
        }
        return $libelles;
    }

}
