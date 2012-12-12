<?php
/**
 * Model for DRMESDetailExport
 *
 */

class DRMESDetailExport extends BaseDRMESDetailExport {
    public function getDetail() {
        
        return $this->getParent()->getParent()->getParent();
    }

    public function getIdentifiantLibelle() {

        return sfCultureInfo::getInstance('fr_FR')->getCountry($this->identifiant);
    }
}
