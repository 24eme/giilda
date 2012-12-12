<?php
/**
 * Model for DRMESDetailVrac
 *
 */

class DRMESDetailVrac extends BaseDRMESDetailVrac {

    protected $vrac = null;

    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->vrac = VracClient::getInstance()->find($this->identifiant);
        }

        return $this->vrac;
    }

    public function getIdentifiantLibelle() {

        return $this->getVrac()->__toString();
    }
}
