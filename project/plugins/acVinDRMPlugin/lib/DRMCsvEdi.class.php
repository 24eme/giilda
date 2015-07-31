<?php

class DRMCsvEdi {

    private $drm = null;

    public function __construct(DRM $drm = null) {
        $this->drm = $drm;
    }

    public function exportEDI() {
        if (!$this->drm) {
            new sfException('Absence de DRM');
        }
        $header = $this->createHeaderEdi();
        $body = $this->createBodyEdi();
        return $header.$body;
    }

    public function createHeaderEdi() {
        return "TYPE;PERIODE;ACCISE;IDPRODUIT;IDMOUVEMENT;QUANTITE;COMPLEMENT\n";
    }

    public function createBodyEdi() {
        $body = $this->createMouvementsEdi();
        $body.= $this->createCrdsEdi();
        $body.= $this->createAnnexesEdi();
        return $body;
    }

    public function createMouvementsEdi() {
        $mouvementsEdi = "";
        $produitsDetails = $this->drm->declaration->getProduitsDetailsSorted(true);
        $debutLigne = "CAVE;" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";
        foreach ($produitsDetails as $hashProduit => $produitDetail) {

            foreach ($produitDetail->entrees as $entreekey => $entreeValue) {
                if ($entreeValue) {

                    $mouvementsEdi.= $debutLigne . $hashProduit . ";" . "entrees/" . $entreekey . ";" . $entreeValue . ";\n";
                }
            }

            foreach ($produitDetail->sorties as $sortiekey => $sortieValue) {
                if ($sortieValue) {
                    if ($sortieValue instanceof DRMESDetails) {
                        foreach ($sortieValue as $sortieDetailKey => $sortieDetailValue) {

                            if ($sortieDetailValue->getVolume()) {
                                $mouvementsEdi.= $debutLigne . $hashProduit . ";" . "sorties/" . $sortiekey . ";" . $sortieDetailValue->getVolume() . ";" . $sortieDetailValue->getIdentifiant() . "\n";
                            }
                        }
                    } else {
                        $mouvementsEdi.= $debutLigne . $hashProduit . ";" . "sorties/" . $sortiekey . ";" . $sortieValue . ";\n";
                    }
                }
            }
        }
        return $mouvementsEdi;
    }

    public function createCrdsEdi() {
        $crdsEdi = "";
        $debutLigne = "CRD;" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";
        foreach ($this->drm->getAllCrdsByRegimeAndByGenre() as $regimeKey => $crdByGenre) {
            foreach ($crdByGenre as $genreKey => $crds) {
                foreach ($crds as $crdKey => $crdDetail) {
                    $centilitrage = str_replace(' ', '', str_replace('Bouteille', '', $crdDetail->detail_libelle));
                    if ($crdDetail->stock_debut) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";stockdebut;" . $crdDetail->stock_debut . ";\n";
                    }
                    if ($crdDetail->entrees_achats) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";entrees/achats;" . $crdDetail->entrees_achats . ";\n";
                    }
                    if ($crdDetail->entrees_retours) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";entrees/retours;" . $crdDetail->entrees_retours . ";\n";
                    }
                    if ($crdDetail->entrees_excedents) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";entrees/excedents;" . $crdDetail->entrees_retours . ";\n";
                    }
                    if ($crdDetail->sorties_utilisations) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";sorties/utilisations;" . $crdDetail->sorties_utilisations . ";\n";
                    }
                    if ($crdDetail->sorties_destructions) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";sorties/destructions;" . $crdDetail->sorties_destructions . ";\n";
                    }
                    if ($crdDetail->sorties_manquants) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";sorties/manquants;" . $crdDetail->sorties_manquants . ";\n";
                    }
                    if ($crdDetail->stock_fin) {
                        $crdsEdi.=$debutLigne . $crdDetail->couleur . '/' . $centilitrage . ";stockfin;" . $crdDetail->stock_fin . ";\n";
                    }
                }
            }
        }
        return $crdsEdi;
    }

    public function createAnnexesEdi() {
        $annexesEdi = "";
        $debutLigneDoc = "ANNEXEDOC;" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";
        $debutLigneNonApurement = "ANNEXENONAPUREMENT;" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";";

        foreach ($this->drm->documents_annexes as $typeDoc => $numsDoc) {
            $annexesEdi.=$debutLigneDoc . $typeDoc . ";debut;;" . $numsDoc->debut . "\n";
            $annexesEdi.=$debutLigneDoc . $typeDoc . ";fin;;" . $numsDoc->fin . "\n";
        }

        foreach ($this->drm->releve_non_apurement as $non_apurement) {
            $annexesEdi.=$debutLigneNonApurement . $non_apurement->numero_document . ";" . $non_apurement->numero_accise . ";;" . $non_apurement->date_emission . "\n";
        }
        if ($this->drm->quantite_sucre) {

            $annexesEdi.="ANNEXESUCRE;" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";sucre;sortie;" . $this->drm->quantite_sucre . ";\n";
        }
        if ($this->drm->observations) {

            $annexesEdi.="ANNEXEOBS;" . $this->drm->periode . ";" . $this->drm->declarant->no_accises . ";Observations;;;" . $this->drm->observations . "\n";
        }

        return $annexesEdi;
    }

}
