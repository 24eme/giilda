<?php
/**
 * Model for DRMESDetailExport
 *
 */

class DRMESDetailExport extends BaseDRMESDetailExport {
    public function getProduitDetail() {

        return $this->getParent()->getProduitDetail();
    }

    public function getIdentifiantLibelle() {

        return ConfigurationClient::getInstance()->getCountry($this->identifiant);
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

    public function getDateEnlevement(){
        if(!$this->_get('date_enlevement')){
            return $this->getDocument()->getDate();
        }

        return $this->_get('date_enlevement');
    }

}
