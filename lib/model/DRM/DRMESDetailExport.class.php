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

        return sfCultureInfo::getInstance('fr_FR')->getCountry($this->identifiant);
    }
}
