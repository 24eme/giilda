<?php
/**
 * Model for DRMESDetailCooperative
 *
 */

class DRMESDetailCooperative extends BaseDRMESDetailCooperative {
    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }

    public function getIdentifiantLibelle() {

        return EtablissementClient::getInstance()->find($this->identifiant)->__toString();
    }
}
