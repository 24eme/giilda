<?php

class DRMClient extends acCouchdbClient {
   
    
    const CONTRATSPRODUITS_NUMERO_CONTRAT = 0;
    const CONTRATSPRODUITS_ETS_NOM = 1;
    const CONTRATSPRODUITS_VOL_TOTAL = 2;
    const CONTRATSPRODUITS_VOL_ENLEVE = 3;

    const VALIDE_STATUS_EN_COURS = '';
    const VALIDE_STATUS_VALIDEE_ENATTENTE = 'VALIDEE';
    const VALIDE_STATUS_VALIDEE_ENVOYEE = 'ENVOYEE';
    const VALIDE_STATUS_VALIDEE_RECUE = 'RECUE';

    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
      return acCouchdbManager::getClient("DRM");
    }

    public function buildId($identifiant, $periode, $version = null) {

      return 'DRM-'.$identifiant.'-'.$this->buildPeriodeAndVersion($periode, $version);
    }

    public function buildVersion($rectificative, $modificative) {

    }

    public function buildCampagne($periode) {
      $annee = $this->getAnnee($periode);
      $mois = $this->getMois($periode);
      if ($annee.$mois < $annee.'08') {
        
        return ($annee-1).'-'.$annee;
      } else {
        
        return $annee.'-'.($annee+1);
      }
    }

    public function buildPeriode($annee, $mois) {

      return sprintf("%04d-%02d", $annee, $mois);
    }

    public function buildPeriodeAndVersion($periode, $version) {
      if($version) {
        return sprintf('%s-%s', $periode, $version);
      }

      return $periode;
    }

    public function getAnnee($periode) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$1', $periode);
    }

    public function getMois($periode) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$2', $periode);
    }

    public function getPeriodeSuivante($periode) {
      $nextMonth = $this->getMois($periode) + 1;
      $nextYear = $this->getAnnee($periode);

      if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
      }
      
      return $this->buildPeriode($nextYear, $nextMonth);
    }
    
    public function getDetailsDefaultDate() {
        $date = date('m/Y');
        $dateArr = explode('/', $date);
        $mois = mktime( 0, 0, 0, $dateArr[0], 1, $dateArr[1] );         
        return date("t",$mois).'/'.$dateArr[0].'/'.$dateArr[1];
    }



    public function findLastByIdentifiantAndPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $drms = $this->viewByIdentifiantPeriode($identifiant, $periode);

      foreach($drms as $id => $drm) {

        return $this->find($id, $hydrate);
      }

      return null;
    }

    public function findByIdentifiantPeriodeAndVersion($identifiant, $periode, $version = null, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

      return $this->find($this->buildId($identifiant, $periode, $version, $hydrate));
    }
    
    public function findOrCreateByIdentifiantAndPeriode($identifiant, $annee, $mois, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      if ($obj = $this->findLastByIdentifiantAndPeriode($identifiant, $this->getPeriode($annee, $mois), $hydrate)) {
        return $obj;
      }

      $obj = new DRM();
      $obj->identifiant = $identifiant;
      $obj->periode = $this->getPeriode($annee, $mois);
      
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

    protected function viewByIdentifiantPeriode($identifiant, $periode) {
      $annee = $this->getAnnee($periode);
      $mois = $this->getMois($periode);

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
          $vol_restant = $row->value[self::CONTRATSPRODUITS_VOL_TOTAL] - $row->value[self::CONTRATSPRODUITS_VOL_ENLEVE];
          $volume = '['.$row->value[self::CONTRATSPRODUITS_VOL_ENLEVE].'/'.$row->value[self::CONTRATSPRODUITS_VOL_TOTAL].']';
          $volume = ($row->value[self::CONTRATSPRODUITS_VOL_ENLEVE]=='')? '[0/'.$row->value[self::CONTRATSPRODUITS_VOL_TOTAL].']' : $volume;
        $vracs[$row->id] = $row->value[self::CONTRATSPRODUITS_ETS_NOM].
                ' ('.$row->value[self::CONTRATSPRODUITS_NUMERO_CONTRAT].') '.
                 $vol_restant.' hl '.
                 $volume;
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

    public function createDoc($identifiant, $periode = null) {
        $historique = new DRMHistorique($identifiant);
        if (!$periode) {
            $drm = $this->createNewDoc($historique);
        } else {
            $drm = $this->createDocByPeriode($historique, $periode);
        }
        return $drm;
    }
    
    private function createNewDoc($historique)
    {
        $lastDRM = $historique->getLastDRM();
        if ($lastDRM && $drm = DRMClient::getInstance()->find(key($lastDRM))) {
            if ($drm->isValidee()) {
                $drm = $drm->generateSuivante($this->getCurrentPeriode());
            }
        } else {
            $drm = new DRM();
            $drm->identifiant = $historique->getIdentifiant();
            $drm->periode = $this->getCurrentPeriode();
        }
        return $drm;
    }
    
    private function createDocByPeriode($historique, $periode)
    {
        $prev_drm = $historique->getPrevByPeriode($periode);
        $next_drm = $historique->getNextByPeriode($periode);
        if ($prev_drm) {
           $prev_drm = DRMClient::getInstance()->find($prev_drm[DRMHistorique::VIEW_INDEX_ID]);
           $drm = $prev_drm->generateSuivante($periode);
        } elseif ($next_drm) {
           $next_drm = DRMClient::getInstance()->find($next_drm[DRMHistorique::VIEW_INDEX_ID]);
           $drm = $next_drm->generateSuivante($periode, false);
        } else {
            $drm = $this->createNewDoc($historique);
            $drm->periode = $periode;
        }
        return $drm;
    }
    
    public function getCurrentPeriode() {

      return date('Y-m');
    }

}
