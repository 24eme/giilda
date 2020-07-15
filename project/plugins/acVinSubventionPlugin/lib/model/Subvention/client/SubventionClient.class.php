<?php

class SubventionClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Subvention");
    }

    public function createDoc($identifiant, $operation) {

        $subvention = new Subvention();
        $subvention->identifiant = $identifiant;
        $subvention->operation = $operation;
        $subvention->constructId();
        $subvention->storeDeclarant();
        $subvention->updateInfosSchema();

        return $subvention;
    }
}
