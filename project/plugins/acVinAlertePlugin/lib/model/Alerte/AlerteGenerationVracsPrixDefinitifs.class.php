
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlerteGenerationVracsPrixDefinitifs
 * @author mathurin
 */
class AlerteGenerationVracsPrixDefinitifs extends AlerteGenerationVrac {

    public function getTypeAlerte() {
        return AlerteClient::VRAC_PRIX_DEFINITIFS;
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
         return $document->hasPrixVariable() && !$document->hasPrixDefinitif();
    }

//    public function creations() {
//        $rows = VracClient::getInstance()->findContatsByWaitForPrixDefinitif($this->getDate());
//        foreach ($rows as $row) {
//            if (Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_delai', $this->getDate()), $row->key[VracOriginalPrixDefinitifView::KEY_DATE_SAISIE])) {
//                $vrac = VracClient::getInstance()->find($row->id, acCouchdbClient::HYDRATE_JSON);
//                $alerte = $this->createOrFindByVrac($vrac);
//                
//                if ($alerte->isNew() || $alerte->isClosed()) {
//                    $alerte->open($this->getDate());
//                }
//                $alerte->save();
//            }
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
//            if (!$vrac->hasPrixDefinitif()) {
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