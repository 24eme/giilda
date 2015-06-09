<?php

/**
 * Model for DRMCrds
 *
 */
class DRMCrds extends BaseDRMCrds {
    
    public function getLibelle() {
        return "";
    }
    
    public function getOrAddCrdNode($genre,$couleur, $litrage,  $stock_debut = null) {
        $crd = $this->add($this->constructKey($genre,$couleur, $litrage,$type_crd));
        $crd->centilitrage = $litrage / 100000;
        $crd->couleur = $couleur;
        $crd->genre = $genre;
        $crd->stock_debut = 0;
        if ($stock_debut) {
            $crd->stock_debut = $stock_debut;
    }
        $contenances = sfConfig::get('app_vrac_contenances');
        $crd->detail_libelle = array_search($crd->centilitrage,$contenances);
        $this->constructKey($genre,$couleur, $litrage,$type_crd);
    }

    public function constructKey($genre,$couleur, $litrage) {
        return $genre.'-'.$couleur . '-' . $litrage;
    }
   
    public function udpateStocksFinDeMois() {
        foreach ($this->getFields() as $crd) {
            $crd->udpateStockFinDeMois();
        }
    }
    

}
