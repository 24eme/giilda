<?php

class GenerationClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Generation");
    }

    public function getId($type_document,$date) {
        return 'Generation-' . $type_document . '-' . $date;
    }



}