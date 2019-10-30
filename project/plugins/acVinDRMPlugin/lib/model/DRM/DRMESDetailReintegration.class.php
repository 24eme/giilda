<?php
/**
 * Model for DRMESDetailExport
 *
 */

class DRMESDetailReintegration extends BaseDRMESDetailExport {
    public function getProduitDetail() {

        return $this->getParent()->getProduitDetail();
    }

    public function getIdentifiantLibelle() {

        return '';
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

    public function setDate($date) {

        return $this->identifiant;
    }

    public function getDate() {

        return $this->identifiant;
    }

}
