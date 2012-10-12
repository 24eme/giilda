<?php

class GenerationClient extends acCouchdbClient {

    const TYPE_DOCUMENT_FACTURES = 'FACTURES';
    const TYPE_DOCUMENT_DS = 'DS';

    const HISTORY_KEYS_STATUS = 0;    
    const HISTORY_VALUES_DATE = 0;
    const HISTORY_VALUES_NBDOC = 1;
    const HISTORY_VALUES_DOCUMENTS = 2;    
    const HISTORY_VALUES_SOMME = 3;
    
    const GENERATION_STATUT_ENCOURS = "En cours";
    const GENERATION_STATUT_GENERE = "Généré";

    public static function getInstance() {
        return acCouchdbManager::getClient("Generation");
    }

    public function getId($type_document,$date) {
        return 'GENERATION-' . $type_document . '-' . $date;
    }
    
    public function findHistory($limit = 10) {
        return acCouchdbManager::getClient()
                        ->limit($limit)
                        ->getView("generation", "history")
                ->rows;
    }
    
     public function findHistoryWithStatusAndType($limit = 10, $status, $type) {
        $views = acCouchdbManager::getClient()
                        ->startkey(array($status, $type))
                        ->endkey(array($status, $type, array()));
           if($limit) $views = $views->limit($limit);
        return $views->getView("generation", "history")->rows;
    }

    public function findHistoryWithType($limit = 10, $type) {
        $allStatus = $this->getAllStatus();
        $results = array();
        foreach ($allStatus as $status) {
            $results = array_merge($this->findHistoryWithStatusAndType($limit,  $status,$type), $results);
        }
        return $results;
    }
    
    public function getGenerationIdEnCours() {
	$rows = acCouchdbManager::getClient()
			->startkey(array(self::GENERATION_STATUT_ENCOURS))
                        ->endkey(array(self::GENERATION_STATUT_ENCOURS, array()))
                        ->getView("generation", "history")
                        ->rows;
	$ids = array();
	foreach($rows as $row) {
		$ids[] = $row->id;
        }
        return $ids;
    }

    public function getDateFromIdGeneration($date) {
        $annee = substr($date,0,4);
        $mois = substr($date,4,2);
        $jour = substr($date,6,2);
        $heure = substr($date,8,2);        
        $minute = substr($date,10,2);
        $seconde = substr($date,12,2);
        return $jour.'/'.$mois.'/'.$annee.' '.$heure.':'.$minute.':'.$seconde;
    }

    public function getAllStatus() {
        return array(self::GENERATION_STATUT_ENCOURS,  self::GENERATION_STATUT_GENERE);
    }
}
