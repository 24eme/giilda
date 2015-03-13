<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationContratsNonSoldes
 * @author mathurin
 */
class AlerteGenerationVracsNonSoldes extends AlerteGenerationVrac {

    public function getTypeAlerte() {

        return AlerteClient::VRAC_NON_SOLDES;
    }
    
    public function execute(){
        $this->updates();
        $this->updatesRelances();
        $this->creations();
    }
    
    public function updates() {
        $this->updatesByDocumentsIds($this->getChanges(),self::TYPE_DOCUMENT);
    }
    
    public function updatesRelances() {
        $this->updatesRelancesForType();
    }
    
    public function creations() {
        $this->creationsByDocumentsIds($this->getChanges(),self::TYPE_DOCUMENT);
    }
    
    public function creation($document) {
       return $this->creationByDocumentId($document,self::TYPE_DOCUMENT,  AlerteClient::STATUT_A_RELANCER);        
    }
    
    public function update($document) {
       return $this->updateByDocumentId($document,self::TYPE_DOCUMENT);        
    }

    public function isInAlerte($document) {  
         return  $document->isVin() && $document->isValidee() && !$document->isSolde();
    }
    
    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_DECLARATIVE;
    }

    
}