<?php

class SubventionClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Subvention");
    }

    public function createOrFind($identifiant, $operation) {

        $subvention = new Subvention();
        $subvention->identifiant = $identifiant;
        $subvention->operation = $operation;
        $subvention->constructId();

        return $subvention;
    }
}
