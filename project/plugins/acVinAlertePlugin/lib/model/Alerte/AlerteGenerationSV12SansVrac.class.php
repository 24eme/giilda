<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteSV12SansVrac
 * @author mathurin
 */
class AlerteGenerationSV12SansVrac extends AlerteGenerationSV12 {

    public function getTypeAlerte() {

        return AlerteClient::SV12_SANS_VRAC;
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
         return $document->hasSansContratOrSansViti();
    }

    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_DECLARATIVE;
    }

}