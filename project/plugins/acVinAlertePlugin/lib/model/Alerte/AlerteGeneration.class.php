<?php

/**
 * Description of class AlerteGeneration
 * @author mathurin
 */
abstract class AlerteGeneration {

    protected $dev = false;
    protected $config = null;
    protected $num_seq = null;
    protected $changes = null;

    public function __construct() {
        $this->config = new AlerteConfig($this->getTypeAlerte());
    }

    public function getConfig() {
        return $this->config;
    }

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
            $alerte->setCreationDate(self::getDate());
            $alerte->type_alerte = $this->getTypeAlerte();
            $alerte->id_document = $id_document;
            $alerte->nb_relances = 0;
            $alerte->date_relance = $this->getConfig()->getOptionDelaiDate('relance_delai', $alerte->date_creation);
            $this->storeDatasRelance($alerte);
        }
        return $alerte;
    }

    public static function getDate() {
 //       return '2013-08-01';
        return date('Y-m-d');
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
        return strstr($type_alerte, '_', true);
    }

    protected function getDataPath() {
        return realpath(dirname(__FILE__) . "/../../../../../data")."/";
    }
    
    protected function getLastSeq() {        
        $path = $this->getDataPath().$this->getTypeAlerte().".txt";
        $seqs_file = file($path);
        $last_line = $seqs_file[count($seqs_file)-1];
        if($last_line === NULL){
            return "110272";
        }
        return substr($last_line, 0, strlen($last_line)-1);
    }

    protected function setlastSeq($seq) {
        $file = $this->getDataPath().$this->getTypeAlerte().".txt";
        $current = file_get_contents($file);
        $current .= $seq . "\n";
        file_put_contents($file, $current);
    }

    public abstract function getTypeAlerte();

    protected abstract function storeDatasRelance(Alerte $alerte);

    public abstract function execute();

    public abstract function creations();
    
    public abstract function updates();

    public abstract function isInAlerte($document);
   
    public function creationByDocumentId($document,$document_type, $statut_ouverture = null){
        $document_master = ($document->isMaster())? $document : $document->getMaster();
        if (!$this->isInAlerte($document_master)) {

            return null;
        }
        $date_saisie = ($document->exist('valide'))? $document->valide->date_saisie : ($document->exist('date_saisie')? $document->date_saisie : $document->date_emission);

        if (!Date::supEqual($this->getConfig()->getOptionDelaiDate('creation_delai', self::getDate()), $date_saisie)) {
            return null;
        }
        
        $fct_name = 'createOrFindBy'.$document_type;
        $alerte = $this->{$fct_name}($document_master);
        
        if (!$alerte->isNew() && !$alerte->isClosed()) {
            return $alerte;
        }
        if($statut_ouverture){
               $alerte->updateStatut($statut_ouverture, null, self::getDate());
        }
        else
        {
            $alerte->open(self::getDate());
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
       
        $alerte->updateStatut(AlerteClient::STATUT_FERME, 'Changement automatique au statut fermer', self::getDate());
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
            $relance = Date::supEqual(self::getDate(), $alerte->date_relance);
            if ($relance) {
                $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, null, self::getDate());
                $alerte->save();
                return $alerte;
            }
            $relance_date = $this->getConfig()->getOption('relance_delai');
            if($relance_date && Date::supEqual($relance_date, self::getDate())){
                $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, 'Changement automatique au statut relance', self::getDate());
                $alerte->save();
                return $alerte;
            }            
            if (Date::supEqual($this->getConfig()->getOptionDelaiDate('relance_delai', $this->getDate()), $alerte->date_relance)) {
                $alerte->updateStatut(AlerteClient::STATUT_A_RELANCER, 'Changement automatique au statut à relancer', self::getDate());
                $alerte->save();
                return $alerte;
            }
            
        }
        return;
    }

}
