<?php

class GenerationClient extends acCouchdbClient {

    const HISTORY_KEYS_ID = 0;
    
    const HISTORY_VALUES_DATE = 0;
    const HISTORY_VALUES_NBDOC = 1;
    const HISTORY_VALUES_DOCUMENTS = 2;    
    const HISTORY_VALUES_SOMME = 3;
    
    public static function getInstance() {
        return acCouchdbManager::getClient("Generation");
    }

    public function getId($type_document,$date) {
        return 'Generation-' . $type_document . '-' . $date;
    }
    
    public function findHistory($limit = 10) {
        return acCouchdbManager::getClient()
                        ->limit($limit)
                        ->getView("generation", "history")
                ->rows;
    }
}