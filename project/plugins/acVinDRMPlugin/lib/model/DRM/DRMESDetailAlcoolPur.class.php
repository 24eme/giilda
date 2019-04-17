<?php
/**
 * Model for DRMESDetailExport
 *
 */

class DRMESDetailAlcoolPur extends BaseDRMESDetailAlcoolPur {
    public function getProduitDetail() {

        return $this->getParent()->getProduitDetail();
    }

    public function getIdentifiantLibelle() {

        return "";
    }

    public function setKey($k) {
        $this->key = $k;
    }

    public function getKey() {
        if (!isset($this->key) || !$this->key) {
            if (!($this->key = parent::getKey())) {
                $this->key = $this->identifiant.'-'.uniqid();
            }
        }

        return $this->key;
    }

}
