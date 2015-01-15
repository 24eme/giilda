<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
class AlerteGenerationDSNonValidee extends AlerteGenerationDS {

    protected $last_periode = null;

    public function getTypeAlerte() {

        return AlerteClient::DS_NON_VALIDEE;
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
       return $this->creationByDocumentId($document,self::TYPE_DOCUMENT,  AlerteClient::STATUT_A_RELANCER);
    }
    
    public function update($document) {        
        return $this->updateByDocumentId($document,self::TYPE_DOCUMENT);        
    }

    public function isInAlerte($document) {
         return $document->isStatutASaisir();
    }
    
    public function getTypeRelance() {
        return RelanceClient::TYPE_RELANCE_DECLARATIVE;
    }
  
}