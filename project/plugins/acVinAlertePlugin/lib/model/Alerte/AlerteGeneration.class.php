<?php

/**
 * Description of class AlerteGeneration
 * @author mathurin
 */
abstract class AlerteGeneration {
    
    protected $dev = false;

    public function isDev() {

        return $this->dev === true;
    }

    public function setModeDev($mode) {
        $this->dev = $mode;
    }

    public function getAlertesOpen() {
        return AlerteHistoryView::getInstance()->findByTypeAndStatuts($this->getTypeAlerte(), AlerteClient::$statutsOpen);
    }

    public function getAlertesRelancable() {
        return AlerteHistoryView::getInstance()->findByTypeAndStatuts($this->getTypeAlerte(), AlerteClient::$statutsRelancable);
    }

    public function getAlerte($id_document) {
        return AlerteClient::getInstance()->find(AlerteClient::getInstance()->buildId($this->getTypeAlerte(), $id_document));
    }

    public function createOrFind($id_document) {        
        $alerte = $this->getAlerte($id_document);        
        if (!$alerte) {
            $alerte = new Alerte();
            $alerte->setCreationDate($this->getDate());
            $alerte->type_alerte = $this->getTypeAlerte();
            $alerte->id_document = $id_document;          
            $alerte->buildFirstDateRelance();
            $this->storeDatasRelance($alerte);
        }
        return $alerte;
    }

    public function getDate() {
        return AlerteDateClient::getInstance()->getDate();
    }

    protected function getAlerteForDocument($document_id) {
       return AlerteClient::getInstance()->findByTypeAndIdDocument($this->getTypeAlerte(),$document_id);
    }
    
    protected function getChanges() {
        
        if(is_null($this->changes)) {
            $this->createChanges();
        }
        return $this->changes;
    }
    
    private function createChanges() {
        $this->num_seq = $this->getlastSeq();
        $args = array('since' => $this->num_seq,'type' => $this->getTypeDocument());
        $changes = AlerteClient::getInstance()->filter('app/type', $args)->getChanges();
        $this->changes = array();
        foreach ($changes->results as $change) {
            if(isset($change->deleted)){
                continue;
            }
            $this->changes[] = $change->id;
        }   
        if($changes->last_seq > $this->num_seq) $this->setLastSeq($changes->last_seq);
    }
    
    private function getTypeDocument(){
        $type_alerte = $this->getTypeAlerte();
        $type =  strstr($type_alerte, '_', true);
        return strtoupper(substr($type, 0,1)).strtolower(substr($type, 1));
    }

    protected function getDataPath() {
        return realpath(dirname(__FILE__) . "/../../../../../data")."/";
    }
    
    protected function getLastSeq() { 
        $ags = AlerteGenerationSequencesClient::getInstance()->findByAlerteType($this->getTypeAlerte());
        if(!$ags){
            $ags = new AlerteGenerationSequences();
            $ags->type_alerte = $this->getTypeAlerte();
            $ags->revisions->add(null,self::FIRST_SEQ);
            $ags->save();
        }
        return $ags->getLastRevision();
    }

    protected function setlastSeq($seq) {
        $ags = AlerteGenerationSequencesClient::getInstance()->findByAlerteType($this->getTypeAlerte());
        $ags->revisions->add(null,$seq);
        $ags->save();
    }

    public abstract function getTypeAlerte();
    
    protected abstract function storeDatasRelance(Alerte $alerte);

    public abstract function executeCreations();
    
    public abstract function executeUpdates();

    public abstract function creations();
    
    public abstract function updates();

    public abstract function isInAlerte($document);
   
    public function creationByDocumentId($document,$document_type, $statut_ouverture = null){
        $document_master = ($document->isMaster())? $document : $document->getMaster();
        if (!$this->isInAlerte($document_master)) {

            return null;
        }
        $date_saisie = ($document->exist('valide'))? $document->valide->date_saisie : ($document->exist('date_saisie')? $document->date_saisie : $document->date_emission);

        if (!Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_delai', $this->getDate()), $date_saisie)) {
            return null;
        }
        
        $fct_name = 'createOrFindBy'.$document_type;
        $alerte = $this->{$fct_name}($document_master);
        
        if (!$alerte->isNew() && !$alerte->isClosed()) {
            return $alerte;
        }
        if($statut_ouverture){
               $alerte->updateStatut($statut_ouverture, null, $this->getDate());
        }
        else
        {
            $alerte->open($this->getDate());
        }
        $alerte->type_relance = $this->getTypeRelance();
        $alerte->save();

        return $alerte;
    }
    
    public function updateByDocumentId($document,$document_type){         
        $alerte = $this->getAlerteForDocument($document->_id);        
        if(!$alerte) return;
        $document_master = ($document->isMaster())? $document : $document->getMaster();
        
        if(!$alerte->isOpen()){            
            return $alerte;
        }
       
        $alerte->updateStatut(AlerteClient::STATUT_FERME, 'Changement automatique au statut fermer', $this->getDate());
        $alerte->save();
        
        return $alerte;
        }
        
    public function creationsByDocumentsIds(array $documents_id,$document_type) {
        foreach ($documents_id as $id_doc) {
            $class_name = $document_type.'Client';
            $this->creation($class_name::getInstance()->getMaster($id_doc));
        }
    }
    
    public function updatesByDocumentsIds(array $documents_id,$document_type) {        
        foreach ($documents_id as $doc_id) {
           $class_name = $document_type.'Client';
           $this->update($class_name::getInstance()->getMaster($doc_id));
        }
    }
    
    public function updatesRelancesForType(){
        foreach ($this->getAlertesRelancable() as $alerteView) {
            $alerte = AlerteClient::getInstance()->find($alerteView->id);       
            if(!$alerte) return $alerte;
            if(!$alerte->isOpen()){            
                return $alerte;
            }
            $relance = Date::supEqual($this->getDate(), $alerte->date_relance);
            if ($relance) {
                $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, null, $this->getDate());
                $alerte->save();
                return $alerte;
            }
            $relance_date = $this->getConfig()->getOption('relance_delai');
            if($relance_date && Date::supEqual($relance_date, $this->getDate())){
                $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, 'Changement automatique au statut à relancé', $this->getDate());
                $alerte->save();
                return $alerte;
            }            
            if (Date::supEqual($this->getConfig()->getOptionDelaiDate('relance_delai', $this->getDate()), $alerte->date_relance)) {
                $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, 'Changement automatique au statut à relancer', $this->getDate());
                $alerte->save();
                return $alerte;
            }
            
        }
        return;
    }

    
    public function getFirstCampagneForImport(){
        return "2012-2013";
    }
    
}
