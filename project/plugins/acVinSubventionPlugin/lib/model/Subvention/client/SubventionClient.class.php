<?php

class SubventionClient extends acCouchdbClient {

    const DOCUMENTRADIX = "SUBVENTION";

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

    public function findByEtablissementSortedByDate($identifiant){
      $subventions = $this->startkey(sprintf(self::DOCUMENTRADIX."-%s-", $identifiant))
                      ->endkey(sprintf(self::DOCUMENTRADIX."-%s-Z", $identifiant))
                      ->execute(acCouchdbClient::HYDRATE_DOCUMENT)->getDatas();
      $subventionsDocs = array();

      foreach ($subventions as $key => $subvention) {
        if($subvention->identifiant == $identifiant){
            if(!array_key_exists($subvention->operation,$subventionsDocs)){
              $subventionsDocs[$subvention->operation] = array();
            }
            $subventionsDocs[$subvention->operation][$subvention->date_modification] = $subvention;
        }
      }
      foreach ($subventionsDocs as $sub => $subventions) {
        ksort($subventions);
      }
      return $subventionsDocs;
    }

}
