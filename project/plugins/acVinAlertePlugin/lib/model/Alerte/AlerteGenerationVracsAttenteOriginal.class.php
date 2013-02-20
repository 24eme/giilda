<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsAttenteOriginal
 * @author mathurin
 */
class AlerteGenerationVracsAttenteOriginal extends AlerteGenerationVrac {

    public function getTypeAlerte() {

        return AlerteClient::VRAC_ATTENTE_ORIGINAL;
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
         return $document->isEnAttenteDOriginal();
    }

//    public function creations() {
//        $rows = VracClient::getInstance()->retreiveByWaitForOriginal();
//        foreach ($rows as $row) {
//            if (!Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_delai', $this->getDate()), $row->key[VracOriginalPrixDefinitifView::KEY_DATE_SAISIE])) {
//
//                continue;
//            }
//
//            $vrac = VracClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
//            $alerte = $this->createOrFindByVrac($vrac);
//
//            if ($alerte->isNew() || $alerte->isClosed()) {
//                $alerte->open($this->getDate());
//            }
//            $alerte->save();
//        }
//    }
//
//    public function updates() {
//        foreach ($this->getAlertesOpen() as $alerteView) {
//            $id_document = $alerteView->key[AlerteHistoryView::KEY_ID_DOCUMENT_ALERTE];
//            $vrac = VracClient::getInstance()->find($id_document, acCouchdbClient::HYDRATE_JSON);
//            if (!$vrac) {
//
//                continue;
//            }
//
//            if ($vrac->attente_original) {
//
//                continue;
//            }
//
//            $alerte = AlerteClient::getInstance()->find($alerteView->id);
//            $alerte->updateStatut(AlerteClient::STATUT_FERME, AlerteClient::MESSAGE_AUTO_FERME, $this->getDate());
//            $alerte->save();
//        }
//        parent::updates();
//    }

}