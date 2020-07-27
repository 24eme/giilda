<?php

class SubventionClient extends acCouchdbClient {

    const DOCUMENTRADIX = "SUBVENTION";

    const STATUT_VALIDE = "VALIDE";
    const STATUT_APPROUVE = "APPROUVE";
    const STATUT_APPROUVE_PARTIELLEMENT = "APPROUVE_PARTIELLEMENT";
    const STATUT_REFUSE = "REFUSE";

    public static $statuts = array(
        self::STATUT_VALIDE => "En attente de validation",
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

    public function findByEtablissementAndOperation($identifiant,$operation){
      return $this->find('SUBVENTION-'.$identifiant.'-'.$operation);
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

    public function findByAllSortedByDate(){
      $subventions = $this->startkey(self::DOCUMENTRADIX."-")
                      ->endkey(self::DOCUMENTRADIX."-Z")
                      ->execute(acCouchdbClient::HYDRATE_DOCUMENT)->getDatas();
      $subventionsDocs = array();

      foreach ($subventions as $key => $subvention) {
            if(!array_key_exists($subvention->operation,$subventionsDocs)){
              $subventionsDocs[$subvention->operation] = array();
            }
            $subventionsDocs[$subvention->operation][$subvention->date_modification] = $subvention;

      }
      foreach ($subventionsDocs as $sub => $subventions) {
        ksort($subventions);
      }
      return $subventionsDocs;
    }

    public function getXlsFileName($operation){
      return "formulaire_subvention_".strtolower($operation).".xlsx";
    }

    public function getDefaultXlsPath($operation){
      return sfConfig::get('sf_data_dir')."/subventions/".$this->getXlsFileName($operation);
    }

}
