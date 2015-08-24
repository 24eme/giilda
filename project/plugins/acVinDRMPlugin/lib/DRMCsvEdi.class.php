<?php

class DRMCsvEdi {

    public $erreurs = array();
    public $statut = null;

    const STATUT_ERREUR = 'ERREUR';
    const STATUT_VALIDE = 'VALIDE';
    const STATUT_WARNING = 'WARNING';
    const TYPE_CAVE = 'CAVE';
    const TYPE_CRD = 'CRD';
    const TYPE_ANNEXEDOC = 'ANNEXEDOC';
    const TYPE_ANNEXENONAPUREMENT = 'ANNEXENONAPUREMENT';
    const TYPE_ANNEXESUCRE = 'ANNEXESUCRE';
    const TYPE_ANNEXEOBS = 'ANNEXEOBS';
    const CSV_TYPE = 0;
    const CSV_PERIODE = 1;
    const CSV_NUMACCISE = 2;
    const CSV_CAVE_CERTIFICATION = 3;
    const CSV_CAVE_GENRE = 4;
    const CSV_CAVE_APPELLATION = 5;
    const CSV_CAVE_MENTION = 6;
    const CSV_CAVE_LIEU = 7;
    const CSV_CAVE_COULEUR = 8;
    const CSV_CAVE_CEPAGE = 9;
    const CSV_CAVE_CATEGORIE_MOUVEMENT = 10;
    const CSV_CAVE_TYPE_MOUVEMENT = 11;
    const CSV_CAVE_VOLUME = 12;
    const CSV_CAVE_COMPLEMENT = 13;

    protected static $permitted_types = array(self::TYPE_CAVE,
        self::TYPE_CRD,
        self::TYPE_ANNEXEDOC,
        self::TYPE_ANNEXENONAPUREMENT,
        self::TYPE_ANNEXESUCRE,
        self::TYPE_ANNEXEOBS);
    private $drm = null;
    protected static $genres = array('MOU' => 'Mousseux', 'EFF' => 'Effervescent', 'TRANQ' => 'Tranquille');

    public function __construct(DRM $drm = null) {
        $this->drm = $drm;
    }

    /**
     * CHECK DU CSV
     */
    public function checkCSV($csv) {
        $this->checkCSVIntegrity($csv);
        if (count($this->erreurs)) {
            $this->statut = self::STATUT_ERREUR;
            return;
        }
        $this->checkImportMouvementsFromCSV($csv);
        if (count($this->erreurs)) {
            $this->statut = self::STATUT_WARNING;
            return;
        }
        $this->statut = self::STATUT_VALIDE;
    }

    /**
     * IMPORT DEPUIS LE CSV
     */
    public function importCSV($csv) {
        $this->importMouvementsFromCSV($csv);
    }

    private function checkCSVIntegrity($csv) {
        $ligne_num = 1;
        foreach ($csv->getCsv() as $csvRow) {
            if ($ligne_num == 1 && $csvRow[self::CSV_TYPE] == 'TYPE') {
                $ligne_num++;
                continue;
            }
            if (!in_array($csvRow[self::CSV_TYPE], self::$permitted_types)) {
                $this->erreurs[] = $this->createWrongFormatTypeError($ligne_num, $csvRow);
            }
            if (!preg_match('/^[0-9]{6}$/', $csvRow[self::CSV_PERIODE])) {
                $this->erreurs[] = $this->createWrongFormatPeriodeError($ligne_num, $csvRow);
            }
            if (!preg_match('/^FR0[0-9]{10}$/', $csvRow[self::CSV_NUMACCISE])) {
                $this->erreurs[] = $this->createWrongFormatNumAcciseError($ligne_num, $csvRow);
            }
            $ligne_num++;
        }
    }

    private function checkImportMouvementsFromCSV($csv) {
        return $this->importMouvementsFromCSV($csv, true);
    }

    private function checkImportCrdsFromCSV($csv, &$erreur_array) {
        foreach ($csv->getCsv() as $csvRow) {
            
        }
    }

    private function checkImportAnnexesFromCSV($csv, &$erreur_array) {
        foreach ($csv->getCsv() as $csvRow) {
            
        }
    }

    private function importMouvementsFromCSV($csv, $just_check = false) {
        $configuration = ConfigurationClient::getCurrent();
        $all_produits = $configuration->declaration->getProduitsAll();

        $num_ligne = 1;
        foreach ($csv->getCsv() as $csvRow) {
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
                $this->erreurs[] = $this->productNotFoundError($num_ligne, $csvRow);
                $num_ligne++;
                continue;
            }

            $detailsConfiguration = $configuration->declaration->getDetailConfiguration();
            $cat_mouvement = $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT];
            $type_mouvement = $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT];
            if (!$detailsConfiguration->exist($cat_mouvement)) {
                $this->erreurs[] = $this->categorieMouvementNotFoundError($num_ligne, $csvRow);
                $num_ligne++;
                continue;
            }
            if (!$detailsConfiguration->get($cat_mouvement)->exist($type_mouvement)) {
                $this->erreurs[] = $this->typeMouvementNotFoundError($num_ligne, $csvRow);
                $num_ligne++;
                continue;
            }
            if (!$just_check) {
                $drm_cepage = $this->drm->getOrAdd($founded_produit->getHash());
                $hasDetails = $detailsConfiguration->get($cat_mouvement)->get($type_mouvement)->hasDetails();
                $drmDetails = $drm_cepage->getOrAdd('details')->getOrAdd('DEFAUT');
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
        if (!$just_check) {
            $this->drm->save();
        }
    }

    private function importCrdsFromCSV($csv) {
        
    }

    private function importAnnexesFromCSV($csv) {
        
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
        $genre = null;
        if ($csvRow[self::CSV_CAVE_GENRE] != 'Tranquille') {
            $genre = $csvRow[self::CSV_CAVE_GENRE];
        }
        if ($genre == 'Effervescent') {
            $genre = 'Fines bulles';
        }
        $libelles = array($csvRow[self::CSV_CAVE_CERTIFICATION],
            $genre,
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

    public function exportEDI() {
        if (!$this->drm) {
            new sfException('Absence de DRM');
        }
        $header = $this->createHeaderEdi();
        $body = $this->createBodyEdi();
        return $header . $body;
    }

    public function createHeaderEdi() {
        return "TYPE;PERIODE;ACCISE;CERTIFICATION / Couleur Capsule;GENRE / Centilitrage;APPELLATION;MENTION;LIEU;COULEUR;CEPAGE;Catégorie Mouvement;Type Mouvement;QUANTITE;COMPLEMENT\n";
    }

    public function createBodyEdi() {
        $body = $this->createMouvementsEdi();
        $body.= $this->createCrdsEdi();
        $body.= $this->createAnnexesEdi();
        return $body;
    }

    public function getProduitCSV($produitDetail) {
        $cepageConfig = $produitDetail->getCepage()->getConfig();

        $certification = $cepageConfig->getCertification()->getLibelle();
        $genre = self::$genres[$cepageConfig->getGenre()->getKey()];
        $appellation = $cepageConfig->getAppellation()->getLibelle();
        $mention = $cepageConfig->getMention()->getLibelle();
        $lieu = $cepageConfig->getLieu()->getLibelle();
        $couleur = $cepageConfig->getCouleur()->getLibelle();
        $cepage = $cepageConfig->getCepage()->getLibelle();

        return $certification . ";" . $genre . ";" . $appellation . ";" . $mention . ";" . $lieu . ";" . $couleur . ";" . $cepage;
    }

    private function getLibelleDetail($keyDetail) {
        if ($keyDetail == 'vrac_details') {
            return 'contrat';
        }
        return str_replace('_details', '', $keyDetail);
    }

    public function createMouvementsEdi() {
        $mouvementsEdi = "";
        $produitsDetails = $this->drm->declaration->getProduitsDetailsSorted(true);
        $debutLigne = self::TYPE_CAVE . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";

        foreach ($produitsDetails as $hashProduit => $produitDetail) {

            foreach ($produitDetail->entrees as $entreekey => $entreeValue) {
                if ($entreeValue) {

                    $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "entrees;" . $entreekey . ";" . $entreeValue . ";\n";
                }
            }

            foreach ($produitDetail->sorties as $sortiekey => $sortieValue) {

                if ($sortieValue) {
                    if ($sortieValue instanceof DRMESDetails) {
                        foreach ($sortieValue as $sortieDetailKey => $sortieDetailValue) {
                            if ($sortieDetailValue->getVolume()) {
                                $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "sorties;" . $this->getLibelleDetail($sortiekey) . ";" . $sortieDetailValue->getVolume() . ";" . $sortieDetailValue->getIdentifiant() . "\n";
                            }
                        }
                    } else {
                        if (!$produitDetail->getConfig()->get('sorties')->get($sortiekey)->hasDetails()) {
                            $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "sorties;" . $sortiekey . ";" . $sortieValue . ";\n";
                        }
                    }
                }
            }
        }
        return $mouvementsEdi;
    }

    public function createCrdsEdi() {
        $crdsEdi = "";
        $debutLigne = self::TYPE_CRD . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";
        foreach ($this->drm->getAllCrdsByRegimeAndByGenre() as $regimeKey => $crdByGenre) {
            foreach ($crdByGenre as $genreKey => $crds) {
                foreach ($crds as $crdKey => $crdDetail) {
                    $centilitrage = str_replace(' ', '', str_replace('Bouteille', '', $crdDetail->detail_libelle));
                    if ($crdDetail->stock_debut) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;stock;debut;" . $crdDetail->stock_debut . ";\n";
                    }
                    if ($crdDetail->entrees_achats) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;entrees;achats;" . $crdDetail->entrees_achats . ";\n";
                    }
                    if ($crdDetail->entrees_retours) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;entrees;retours;" . $crdDetail->entrees_retours . ";\n";
                    }
                    if ($crdDetail->entrees_excedents) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;entrees;excedents;" . $crdDetail->entrees_retours . ";\n";
                    }
                    if ($crdDetail->sorties_utilisations) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;sorties;utilisations;" . $crdDetail->sorties_utilisations . ";\n";
                    }
                    if ($crdDetail->sorties_destructions) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;sorties;destructions;" . $crdDetail->sorties_destructions . ";\n";
                    }
                    if ($crdDetail->sorties_manquants) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;sorties;manquants;" . $crdDetail->sorties_manquants . ";\n";
                    }
                    if ($crdDetail->stock_fin) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . ";" . $centilitrage . ";;;;;;stock;fin;" . $crdDetail->stock_fin . ";\n";
                    }
                }
            }
        }
        return $crdsEdi;
    }

    public function createAnnexesEdi() {
        $annexesEdi = "";
        $debutLigneDoc = self::TYPE_ANNEXEDOC . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";
        $debutLigneNonApurement = self::TYPE_ANNEXENONAPUREMENT . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";

        foreach ($this->drm->documents_annexes as $typeDoc => $numsDoc) {
            $annexesEdi.=$debutLigneDoc . $typeDoc . ";;;;;;;;debut;;" . $numsDoc->debut . "\n";
            $annexesEdi.=$debutLigneDoc . $typeDoc . ";;;;;;;;fin;;" . $numsDoc->fin . "\n";
        }

        foreach ($this->drm->releve_non_apurement as $non_apurement) {
            $annexesEdi.=$debutLigneNonApurement . $non_apurement->numero_document . ";;;;;;;;" . $non_apurement->numero_accise . ";;" . $non_apurement->date_emission . "\n";
        }
        if ($this->drm->quantite_sucre) {

            $annexesEdi.=self::TYPE_ANNEXESUCRE . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";sucre;;;;;;;sortie;;" . $this->drm->quantite_sucre . ";\n";
        }
        if ($this->drm->observations) {

            $annexesEdi.=self::TYPE_ANNEXEOBS . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";Observations;;;;;;;;;;" . $this->drm->observations . "\n";
        }

        return $annexesEdi;
    }

}
