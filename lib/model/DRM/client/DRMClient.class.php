<?php

class DRMClient extends acCouchdbClient {
   
    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
      return acCouchdbManager::getClient("DRM");
    }

    public function getId($identifiant, $campagne, $rectificative = null) {
      return 'DRM-'.$identifiant.'-'.$this->getCampagneAndRectificative($campagne, $rectificative);
    }

    public function getCampagne($annee, $mois) {

      return sprintf("%04d-%02d", $annee, $mois);
    }

    public function getAnnee($campagne) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$1', $campagne);
    }

    public function getMois($campagne) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$2', $campagne);
    }
    
    public function getDetailsDefaultDate() {
        $date = date('m/Y');
        $dateArr = explode('/', $date);
        $mois = mktime( 0, 0, 0, $dateArr[0], 1, $dateArr[1] );         
        return date("t",$mois).'/'.$dateArr[0].'/'.$dateArr[1];
    }

    public function getCampagneAndRectificative($campagne, $rectificative = null) {
      if($rectificative  && $rectificative > 0) {
        return $campagne.'-R'.sprintf("%02d", $rectificative);
      } 
      return $campagne;
    }

    public function findLastByIdentifiantAndCampagne($identifiant, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $drms = $this->viewByIdentifiantCampagne($identifiant, $campagne);

      foreach($drms as $id => $drm) {

        return $this->find($id, $hydrate);
      }

      return null;
    }

    public function findByIdentifiantCampagneAndRectificative($identifiant, $campagne, $rectificative = null, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

      return $this->find($this->getId($identifiant, $campagne, $rectificative, $hydrate));
    }
    
    public function retrieveOrCreateByIdentifiantAndCampagne($identifiant, $annee, $mois, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      if ($obj = $this->findLastByIdentifiantAndCampagne($identifiant, $this->getCampagne($annee, $mois), $hydrate)) {
        return $obj;
      }

      $obj = new DRM();
      $obj->identifiant = $identifiant;
      $obj->campagne = $this->getCampagne($annee, $mois);
      
      return $obj;
    }

    public function findByInterproDate($interpro, $date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $drm = array();
      foreach ($this->viewByInterproDate($interpro, $date) as $id => $key) {
	$drm[] = $this->find($id);
      }
      return $drm;
    }

    protected function viewByInterproDate($interpro, $date) {
      $rows = acCouchdbManager::getClient()
	->startkey(array($interpro, $date))
	->endkey(array($interpro, array()))
	->getView("drm", "date")
	->rows;

      $drms = array();

      foreach($rows as $row) {
        $drms[$row->id] = $row->key;
      }
      
      return $drms;
    }

    protected function viewByIdentifiantCampagne($identifiant, $campagne) {
      $annee = $this->getAnnee($campagne);
      $mois = $this->getMois($campagne);

      $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $annee, $mois))
              ->endkey(array($identifiant, $annee, $mois, array()))
              ->reduce(false)
              ->getView("drm", "all")
              ->rows;
      
      $drms = array();

      foreach($rows as $row) {
        $drms[$row->id] = $row->key;
      }
      
      krsort($drms);
      
      return $drms;
    }
    
    public function getContratsFromProduit($vendeur_identifiant, $produit)
    {
       if(substr($produit, 0, 1) == "/") {
           $produit = substr($produit, 1);
       }
       $rows = acCouchdbManager::getClient()
            ->startkey(array(VracClient::STATUS_CONTRAT_NONSOLDE, $vendeur_identifiant, $produit))
              ->endkey(array(VracClient::STATUS_CONTRAT_NONSOLDE, $vendeur_identifiant, $produit, array()))
              ->getView("vrac", "contratsFromProduit")
              ->rows;
      $vracs = array();

      foreach($rows as $key => $row) {
          $volume = $row->value[3].'/'.$row->value[2];
          $volume = ($row->value[3]=='')? '0/'.$row->value[2] : $volume;
        $vracs[$row->id] = $row->value[0].' '.$row->value[1].' - '.$volume;
      }      
      return $vracs;       
    }
  
  public function findProduits() {
    return $this->startkey(array("produit"))
              ->endkey(array("produit", array()))->getView('drm', 'produits');
  }
  
  public function getAllProduits() {
    $produits = $this->findProduits()->rows;
    $result = array();
    foreach ($produits as $produit) {
    	$result[] = $produit->key[1];
    }
    return $result;
  }

}
