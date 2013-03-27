<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationDRMStockNegatif
 * @author mathurin
 */
class AlerteGenerationDRMStockNegatif extends AlerteGenerationDRM {

    
    public function getTypeAlerte() {
        return AlerteClient::DRM_STOCK_NEGATIF;
    }

    public function execute(){
        $this->updates();
        $this->creations();
    }
    
    public function updates() {
        $this->updatesByDocumentsIds($this->getChanges(),self::TYPE_DOCUMENT);
    }
    
    public function creations() {
        $this->creationsByDocumentsIds($this->getChanges(),self::TYPE_DOCUMENT);
    }
    
    public function creation($document) {
       return $this->creationByDocumentId($document,self::TYPE_DOCUMENT);        
    }
    
    public function update($document) {        
        return $this->updateByDocumentId($document,self::TYPE_DOCUMENT);        
    }
    
    public function isInAlerte($document) {
         return $document->declaration->hasProduitDetailsWithStockNegatif();
    }
    
    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_DECLARATIVE;
    }

}