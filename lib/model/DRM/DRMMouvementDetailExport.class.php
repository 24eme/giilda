<?php
/**
 * Model for DRMMouvementDetailExport
 *
 */

class DRMMouvementDetailExport extends BaseDRMMouvementDetailExport {
    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }

    public function getIdentifiantLibelle() {

        return sfCultureInfo::getInstance('fr_FR')->getCountry($this->identifiant);
    }
}