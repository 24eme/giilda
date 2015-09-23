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
class DRMExportCsvEdi extends DRMCsvEdi {

    public function __construct(DRM $drm = null) {
        parent::__construct(null, $drm);
    }

    public function exportEDI() {
        if (!$this->drm) {
            new sfException('Absence de DRM');
        }
        $header = $this->createHeaderEdi();
        $body = $this->createBodyEdi();
        return $header . $body;
    }

    private function createHeaderEdi() {
        return "#TYPE;PERIODE;IDENTIFIANT;ACCISE;CERTIFICATION / TYPE PRODUIT; GENRE / COULEUR CAPSULE; APPELLATION / CENTILITRAGE;MENTION;LIEU;COULEUR;CEPAGE;CATEGORIE MOUVEMENT / TYPE DOCUMENT;TYPE MOUVEMENT;VOLUME / QUANTITE;PAYS EXPORT / DATE NON APUREMENT;NUMERO CONTRAT / NUMERO ACCISE DESTINATAIRE NON APUREMENT;TYPE DOCUMENT ACCOMPAGNEMENT;NUMERO DOCUMENT ACCOMPAGNEMENT;OBSERVATIONS\n";
    }

    private function createBodyEdi() {
        $body = $this->createMouvementsEdi();
        $body.= $this->createCrdsEdi();
        $body.= $this->createAnnexesEdi();
        return $body;
    }

    public function getProduitCSV($produitDetail) {
        $cepageConfig = $produitDetail->getCepage()->getConfig();

        $certification = $cepageConfig->getCertification()->getLibelle();
        $genre = $cepageConfig->getGenre()->getLibelle();
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

    private function createMouvementsEdi() {
        $mouvementsEdi = "";
        $produitsDetails = $this->drm->declaration->getProduitsDetailsSorted(true);
        $debutLigne = self::TYPE_CAVE . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";";

        foreach ($produitsDetails as $hashProduit => $produitDetail) {

            foreach ($produitDetail->stocks_debut as $stockdebut_key => $stockdebutValue) {
                if ($stockdebutValue) {
                    $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "stocks_debut;" . $stockdebut_key . ";" . $stockdebutValue . ";\n";
                }
            }

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
                                $complement = $sortieDetailValue->getIdentifiant();
                                $type_doc = ($sortieDetailValue->numero_document) ? $sortieDetailValue->type_document : '';
                                $numero_doc = ($sortieDetailValue->numero_document) ? $sortieDetailValue->numero_document : '';
                                if ($sortiekey == 'export_details') {
                                    $pays = $this->countryList[$sortieDetailValue->getIdentifiant()];
                                    $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "sorties;" . $this->getLibelleDetail($sortiekey) . ";" . $sortieDetailValue->getVolume() . ";" . $pays . ";;" . $type_doc . ";" . $numero_doc . "\n";
                                }
                                if ($sortiekey == 'vrac_details') {
                                    $numero_vrac = str_replace('VRAC-', '', $sortieDetailValue->getIdentifiant());
                                    $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "sorties;" . $this->getLibelleDetail($sortiekey) . ";" . $sortieDetailValue->getVolume() . ";;" . $numero_vrac . ";" . $type_doc . ";" . $numero_doc . "\n";
                                }
                            }
                        }
                    } else {
                        if (!$produitDetail->getConfig()->get('sorties')->get($sortiekey)->hasDetails()) {
                            $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "sorties;" . $sortiekey . ";" . $sortieValue . ";\n";
                        }
                    }
                }
            }

            foreach ($produitDetail->stocks_fin as $stockfin_key => $stockfinValue) {
                if ($stockfinValue) {
                    $mouvementsEdi.= $debutLigne . $this->getProduitCSV($produitDetail) . ";" . "stocks_fin;" . $stockfin_key . ";" . $stockfinValue . ";\n";
                }
            }
        }
        return $mouvementsEdi;
    }

    private function createCrdsEdi() {
        $crdsEdi = "";
        $debutLigne = self::TYPE_CRD . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";";
        foreach ($this->drm->getAllCrdsByRegimeAndByGenre() as $regimeKey => $crdByGenre) {
            foreach ($crdByGenre as $genreKey => $crds) {
                foreach ($crds as $crdKey => $crdDetail) {
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'stock_debut', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'entrees_achats', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'entrees_retours', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'entrees_excedents', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'sorties_utilisations', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'sorties_destructions', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'sorties_manquants', $crdsEdi);
                    $this->createCrdRowEdi($debutLigne, $crdDetail, 'stock_fin', $crdsEdi);
                }
            }
        }
        return $crdsEdi;
    }

    private function createCrdRowEdi($debutLigne, $crdDetail, $type_mvt, &$crdsEdi) {
        if ($crdDetail->$type_mvt) {
            $type_mvt_csv = "";
            switch ($type_mvt) {
                case 'stock_debut':
                case 'stock_fin':
                    $type_mvt_csv = $type_mvt . ";";
                    break;
                default :
                    $type_mvt_csv = str_replace('_', ';', $type_mvt);
                    break;
            }
            $crdsEdi.= $debutLigne . $crdDetail->genre . ";" . $crdDetail->couleur . ";" . $crdDetail->detail_libelle . ";;;;;" . $type_mvt_csv . ";" . $crdDetail->$type_mvt . ";\n";
        }
    }

    private function createAnnexesEdi() {
        $annexesEdi = "";
        $debutLigneAnnexe = self::TYPE_ANNEXE . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";;;;;;;;";

        foreach ($this->drm->documents_annexes as $typeDoc => $numsDoc) {
            $annexesEdi.=$debutLigneAnnexe . $typeDoc . ";debut;;;;;" . $numsDoc->debut . "\n";
            $annexesEdi.=$debutLigneAnnexe . $typeDoc . ";fin;;;;;" . $numsDoc->fin . "\n";
        }

        foreach ($this->drm->releve_non_apurement as $non_apurement) {
            $annexesEdi.=$debutLigneAnnexe . self::TYPE_ANNEXE_NONAPUREMENT . ";;;".$non_apurement->date_emission.";" . $non_apurement->numero_accise . ";;" . $non_apurement->numero_document . "\n";
        }
        if ($this->drm->quantite_sucre) {

            $annexesEdi.=$debutLigneAnnexe .  self::TYPE_ANNEXE_SUCRE . ";;" . $this->drm->quantite_sucre . ";\n";
        }
        if ($this->drm->observations) {

            $annexesEdi.=$debutLigneAnnexe . self::TYPE_ANNEXE_OBSERVATIONS . ";;;;;;;" . $this->drm->observations . "\n";
        }

        return $annexesEdi;
    }

}
