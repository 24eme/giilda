<?php
/**
 * Model for DRMMouvementDetailVrac
 *
 */

class DRMMouvementDetailVrac extends BaseDRMMouvementDetailVrac {
    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }

    public function getIdentifiantLibelle() {

        return VracClient::getInstance()->find($this->identifiant)->__toString();
    }
}