<?php

/**
 * Model for FactureLignes
 *
 */
class FactureLignes extends BaseFactureLignes {

    public function facturerMouvements() {
        foreach ($this as $ligne) {
            $ligne->facturerMouvements();
        }
    }

    public function updateTotaux() {
        foreach ($this as $ligne) {
            $ligne->updateTotaux();
        }
    }

    public function defacturerMouvements() {
        foreach ($this as $ligne) {
            $ligne->defacturerMouvements();
        }
    }

    public function cleanLignes() {
        $lignesToDelete = array();

        foreach($this as $ligne) {
            $ligne->cleanDetails();
            if(!count($ligne->details)) {
                $lignesToDelete[$ligne->getKey()] = true;
            }
        }

        foreach($lignesToDelete as $key => $void) {
            $this->remove($key);
        }
    }

    public function getMontantTva() {
        $montant = 0;
        foreach ($this as $ligne) {
            $montant += $ligne->montant_tva;
        }
        return round($montant, 2);
    }

    public function getMontantsHTByTva() {
        $montantsByTva = [];
        foreach($this as $ligne) {
            $montantsByTva = array_merge_recursive($montantsByTva, $ligne->getMontantsHTByTva());
        }
        return array_map(function($val) { return (is_array($val))? round(array_sum($val), 2) : $val; }, $montantsByTva);
    }

}
