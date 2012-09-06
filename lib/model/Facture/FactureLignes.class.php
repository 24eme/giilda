<?php
/**
 * Model for FactureLignes
 *
 */

class FactureLignes extends BaseFactureLignes {
    
    public function getDocumentOrigine() {
        
        return $this->getDocument()->getDocumentOrigine($this->origine_identifiant);
    }
    
    public function getMouvement() {
        
        return $this->getDocumentOrigine()->findMouvement($this->cle_mouvement);
    }
    
    public function facturerMouvement() {
        
        $this->getMouvement()->facturer();
    }
    
    public function defacturerMouvement() {
        $this->getMouvement()->defacturer();
    }
}