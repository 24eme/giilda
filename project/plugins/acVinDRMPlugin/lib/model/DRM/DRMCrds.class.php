<?php

/**
 * Model for DRMCrds
 *
 */
class DRMCrds extends BaseDRMCrds {

    const FACTLITRAGE = 100000;
    
    public function getLibelle() {
        return "";
    }

    public function getOrAddCrdNode($genre, $couleur, $litrage, $stock_debut = null) {
        $crd = $this->add($this->constructKey($genre, $couleur, $litrage));
        $crd->centilitrage = $litrage / self::FACTLITRAGE;
        $crd->couleur = $couleur;
        $crd->genre = $genre;
        $crd->stock_debut = 0;
        if ($stock_debut) {
            $crd->stock_debut = $stock_debut;
        }
        $contenances = sfConfig::get('app_vrac_contenances');
        $crd->detail_libelle = array_search($crd->centilitrage, $contenances);
        $this->constructKey($genre, $couleur, $litrage);
    }

    public function constructKey($genre, $couleur, $litrage) {
        return $genre . '-' . $couleur . '-' . $litrage;
    }

    public function udpateStocksFinDeMois() {
        foreach ($this->getFields() as $crd) {
            $crd->udpateStockFinDeMois();
        }
    }

    public function crdsInitDefault($genres) {
        if (count($this->getFields())) {
            return;
        }
        $contenances = sfConfig::get('app_vrac_contenances');
        $contenance75 = $contenances['Bouteille 75 cl'] * self::FACTLITRAGE;
          
        foreach ($genres as $genre) {
            $this->getOrAddCrdNode($genre, DRMClient::DRM_VERT, $contenance75);
            $this->getOrAddCrdNode($genre, DRMClient::DRM_BLEU, $contenance75);
            $this->getOrAddCrdNode($genre, DRMClient::DRM_LIEDEVIN, $contenance75);
        }
    }

}
