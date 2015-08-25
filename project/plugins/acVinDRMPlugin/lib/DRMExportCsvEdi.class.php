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
        parent::__construct($drm);
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
        return "TYPE;PERIODE;ACCISE;CERTIFICATION / Couleur Capsule;GENRE / Centilitrage;APPELLATION;MENTION;LIEU;COULEUR;CEPAGE;Catégorie Mouvement;Type Mouvement;QUANTITE;COMPLEMENT\n";
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

    private function createMouvementsEdi() {
        $mouvementsEdi = "";
        $produitsDetails = $this->drm->declaration->getProduitsDetailsSorted(true);
        $debutLigne = self::TYPE_CAVE . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";

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
        $debutLigne = self::TYPE_CRD . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";
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
            $crdsEdi.= $debutLigne . $crdDetail->genre . ";" . $crdDetail->couleur . ";" . $crdDetail->detail_libelle . ";;;;;;" . $type_mvt . ";" . $crdDetail->$type_mvt . ";\n";
        }
    }

    private function createAnnexesEdi() {
        $annexesEdi = "";
        $debutLigneAnnexe = self::TYPE_ANNEXE . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";

        foreach ($this->drm->documents_annexes as $typeDoc => $numsDoc) {
            $annexesEdi.=$debutLigneAnnexe . $typeDoc . ";;;;;;;;debut;;" . $numsDoc->debut . "\n";
            $annexesEdi.=$debutLigneAnnexe . $typeDoc . ";;;;;;;;fin;;" . $numsDoc->fin . "\n";
        }

        foreach ($this->drm->releve_non_apurement as $non_apurement) {
            $annexesEdi.=$debutLigneAnnexe . self::TYPE_ANNEXE_NONAPUREMENT.";" . $non_apurement->numero_document . ";;;;;;;" . $non_apurement->numero_accise . ";;" . $non_apurement->date_emission . "\n";
        }
        if ($this->drm->quantite_sucre) {

            $annexesEdi.=self::TYPE_ANNEXE . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";".self::TYPE_ANNEXE_SUCRE.";;;;;;;sortie;;" . $this->drm->quantite_sucre . ";\n";
        }
        if ($this->drm->observations) {

            $annexesEdi.=self::TYPE_ANNEXE . ";" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";".self::TYPE_ANNEXE_OBSERVATIONS.";;;;;;;;;;" . $this->drm->observations . "\n";
        }

        return $annexesEdi;
    }

}
