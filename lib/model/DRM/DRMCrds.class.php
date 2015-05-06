<?php

/**
 * Model for DRMCrds
 *
 */
class DRMCrds extends BaseDRMCrds {

    public function getOrAddCrdType($couleur, $litrage) {
        $crd = $this->add($this->constructKey($couleur, $litrage));        
        $crd->centilitrage = $litrage / 10000;
        $crd->couleur = $couleur;
        $crd->stock_debut = 1000;
        $this->constructKey($couleur, $litrage);        
    }

    public function constructKey($couleur, $litrage) {
        return $couleur . '-' . $litrage;
    }

}
