<?php

class ConfigurationClient extends acCouchdbClient {
  private static $current = array();
  
  /**
   *
   * @return CurrentClient 
   */
  public static function getInstance() {
      return acCouchdbManager::getClient("CONFIGURATION");
  }

  /**
   *
   * @return Current 
   */
  public static function getCurrent() {
    if (self::$current == null) {
      self::$current = CacheFunction::cache('model', array(ConfigurationClient::getInstance(), 'retrieveCurrent'), array());
    }
    return self::$current;
  }
  
  /**
   *
   * @return Current
   */
  public function retrieveCurrent() {
      $configuration = parent::retrieveDocumentById('CONFIGURATION');
      if (!sfConfig::get('sf_debug')) {
        $configuration->loadAllData();
      }

    return $configuration;
  }
  
  public function findProduitsForAdmin($interpro) {
    return $this->startkey(array($interpro))
              ->endkey(array($interpro, array()))->getView('configuration', 'produits_admin');
  }
  
  public function findProduitsByCertificationAndInterpro($interpro, $certif) {
    return $this->startkey(array($interpro, $certif))
              ->endkey(array($interpro, $certif, array()))->getView('configuration', 'produits_admin');
  }
  
  public function nbProduitsByCertificationDepAndInterpro($interpro, $certif, $dep = "") {
    return $this->startkey(array("produits", $interpro, $dep, $certif))
                ->endkey(array("produits", $interpro, $dep, $certif, array()))
    		    ->reduce(true)
    		    ->group_level(4)
    		    ->getView('configuration', 'produits');
  }

  public function findProduits() {
    return $this->startkey(array("produits"))
              ->endkey(array("produits", array()))->reduce(false)->getView('configuration', 'produits');
  }

  public function findProduitsLieuxByCertification($certif, $interpro, $departement) {

    return $this->startkey(array("lieux", $interpro, $departement, $certif))
              ->endkey(array("lieux", $interpro, $departement, $certif, array()))->reduce(false)->getView('configuration', 'produits');
  }

  public function findProduitsByInterAndDep($interpro, $departement) {
    return $this->startkey(array("produits", $interpro, $departement))
              ->endkey(array("produits", $interpro, $departement, array()))->reduce(false)->getView('configuration', 'produits');
  }

  public function findProduitsByCertification($certif, $interpro, $departement) {
    return $this->startkey(array("produits", $interpro, $departement, $certif))
              ->endkey(array("produits", $interpro, $departement, $certif, array()))->reduce(false)->getView('configuration', 'produits');
  }

  public function findProduitsByLieu($certif, $interpro, $departement, $hash_lieu) {

    if (substr($hash_lieu, 0,1) == "/") {
      $hash_lieu = substr($hash_lieu, 1,strlen($hash_lieu)-1);
    }

    return $this->startkey(array("produits", $interpro, $departement, $certif, $hash_lieu))
              ->endkey(array("produits", $interpro, $departement, $certif, $hash_lieu, array()))->reduce(false)->getView('configuration', 'produits');
  }

  public function findLabelsByCertification($certif, $interpro) {
    return $this->startkey(array("labels", $interpro, "", $certif))
              ->endkey(array("labels", $interpro, "", $certif, array()))->reduce(false)->getView('configuration', 'produits');
  }
  
   public static function getMillesimes() {
        $lastMillesime =  date('Y');
        $result = array();
        for($i=$lastMillesime;$i>=1991;$i--) $result[$i] = $i;
        return $result;
    }
  
}
