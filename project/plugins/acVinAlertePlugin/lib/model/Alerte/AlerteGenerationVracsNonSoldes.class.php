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
         return  $document->isValidee() && !$document->isSolde();
    }

//    public function creations() {
//        $rows = VracClient::getInstance()->retreiveByStatutsTypesAndDate(
//                array(VracClient::STATUS_CONTRAT_NONSOLDE), array(VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE,
//            VracClient::TYPE_TRANSACTION_VIN_VRAC), $this->getConfig()->getOptionDelaiDate('creation_delai', $this->getDate()));
//
//        foreach ($rows as $row) {
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
//            $vrac = VracClient::getInstance()->find($id_document);
//            if(!$vrac) {
//
//                continue;
//            }
//
//            if ($this->valide->statut != VracClient::STATUS_CONTRAT_SOLDE) {
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