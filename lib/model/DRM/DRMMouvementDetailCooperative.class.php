<?php
/**
 * Model for DRMMouvementDetailCooperative
 *
 */

class DRMMouvementDetailCooperative extends BaseDRMMouvementDetailCooperative {
    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }

    public function getIdentifiantLibelle() {

        return EtablissementClient::getInstance()->find($this->identifiant)->__toString();
    }
}