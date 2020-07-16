<?php

class SubventionClient extends acCouchdbClient {

    const STATUT_VALIDE = "VALIDE";
    const STATUT_APPROUVE = "APPROUVE";
    const STATUT_APPROUVE_PARTIELLEMENT = "APPROUVE_PARTIELLEMENT";
    const STATUT_REFUSE = "REFUSE";

    public static $statuts = array(
        self::STATUT_APPROUVE => "Approuvé",
        self::STATUT_APPROUVE_PARTIELLEMENT => "Approuvé partiellement",
        self::STATUT_REFUSE => "Refusé",
    );

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
