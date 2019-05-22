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


    public function getOrAddCrdNode($genre, $couleur, $contenanceEnHl, $libelle = null, $stock_debut = null) {

        $crd = $this->add($this->constructKey($genre, $couleur, $contenanceEnHl, $libelle));

        $crd->setContenance($contenanceEnHl);

        $crd->couleur = $couleur;
        $crd->genre = $genre;
        $crd->stock_debut = 0;
        if ($stock_debut) {
            $crd->stock_debut = $stock_debut;
        }
        $contenances = sfConfig::get('app_vrac_contenances');
        if ($libelle) {
          $crd->detail_libelle = $libelle;
        }else{
          $crd->detail_libelle = array_search($crd->centilitrage, $contenances);
        }

        $this->constructKey($genre, $couleur, $contenanceEnHl, $crd->detail_libelle);
        return $crd;
    }

    public function constructKey($genre, $couleur, $contenanceEnHl, $libelle = null) {
        if ($libelle && preg_match('/bib/i', $libelle)) {
          return $genre . '-' . $couleur . '-BIB' . ($contenanceEnHl*self::FACTLITRAGE);
        }
        return $genre . '-' . $couleur . '-' . ($contenanceEnHl*self::FACTLITRAGE);
    }

    public function udpateStocksFinDeMois() {
        foreach ($this->getFields() as $crd) {
            $crd->udpateStockFinDeMois();
        }
    }

    public function isEmptyNode(){
        return count($this->toArray());
    }

    public function crdsInitDefault($genres) {
        if ($this->isEmptyNode()) {
            return;
        }
        $contenances = sfConfig::get('app_vrac_contenances');
        $contenanceDefault = $contenances['Bouteille 75 cl'];

        foreach ($genres as $genre) {
            $this->getOrAddCrdNode($genre, DRMClient::DRM_DEFAUT, $contenanceDefault);
            $this->getOrAddCrdNode($genre, DRMClient::DRM_VERT, $contenanceDefault);
            $this->getOrAddCrdNode($genre, DRMClient::DRM_BLEU, $contenanceDefault);
            $this->getOrAddCrdNode($genre, DRMClient::DRM_LIEDEVIN, $contenanceDefault);
        }
    }



}
