<?php

/**
 * Model for DRMCrds
 *
 */
class DRMCrds extends BaseDRMCrds {

    public function getOrAddCrdType($couleur, $litrage, $type_crd,  $stock_debut = null) {
        $crd = $this->add($this->constructKey($couleur, $litrage,$type_crd));
        $crd->centilitrage = $litrage / 100000;
        $crd->couleur = $couleur;
        $crd->type_crd = $type_crd;
        $crd->stock_debut = 0;
        if ($stock_debut) {
            $crd->stock_debut = $stock_debut;
        }
        $contenances = sfConfig::get('app_vrac_contenances');
        $crd->detail_libelle = array_search($crd->centilitrage,$contenances);
        $this->constructKey($couleur, $litrage,$type_crd);
    }

    public function constructKey($couleur, $litrage,$type_crd) {
        return $couleur . '-' . $litrage.'-'.$type_crd;
    }
    
    public function udpateStocksFinDeMois() {
        foreach ($this->getFields() as $crd) {
            $crd->udpateStockFinDeMois();
        }
    }

}
