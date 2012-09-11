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
    
    public function getMouvements() {
        
        $mouvements = array();
        
        foreach ($this->origine_mouvements as $value) {
            $mouvements[] = $this->getDocumentOrigine()->findMouvement($value);
        }
        return $mouvements;
    }
    
    public function facturerMouvements() {       
        foreach ($this->getMouvements() as $mouv) {
            $mouv->facturer();
        }
    }
    
    public function defacturerMouvements() {
        foreach ($this->getMouvements() as $mouv) {
               $mouv->defacturer();
        }
    }
    
}
