<?php
/**
 * Model for DRMESDetailVrac
 *
 */

class DRMESDetailVrac extends BaseDRMESDetailVrac {

    protected $vrac = null;


    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->vrac = VracClient::getInstance()->find($this->identifiant);
        }

        return $this->vrac;
    }

    public function getIdentifiantLibelle() {

        return $this->getVrac()->getNumeroArchive();
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
