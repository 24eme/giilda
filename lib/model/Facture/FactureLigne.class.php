<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class FactureLigne
 * @author mathurin
 */
class FactureLigne extends BaseFactureLigne {
    
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
