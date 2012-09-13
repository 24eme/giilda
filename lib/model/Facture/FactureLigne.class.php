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
    
   public function getDocumentOrigine($key) {
        return $this->getDocument()->getDocumentOrigine($key);
    }
    
    public function getMouvements() {
        $mouvements = array();        
        foreach ($this->origine_mouvements as $idDoc => $mouvsKeys) {
            foreach ($mouvsKeys as $mouvKey) {
            $mouvements[] = $this->getDocumentOrigine($idDoc)->findMouvement($mouvKey);
            }
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
