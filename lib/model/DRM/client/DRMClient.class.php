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

    protected $drm_historiques = array();

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

        return DRM::buildVersion($rectificative, $modificative);
    }

    public function getRectificative($version) {

        return DRM::buildRectificative($version);
    }
    
    public function getModificative($version) {

        return DRM::buildModificative($version);
    }

    public function getPeriodes($campagne) {
      $periodes = array();
      $periode = $this->getPeriodeDebut($campagne);
      while($periode != $this->getPeriodeFin($campagne)) {
        $periodes[] = $periode;
        $periode = $this->getPeriodeSuivante($periode);
      }

      $periodes[] = $periode;

      return $periodes;
    }

    public function buildDate($periode) {
        
        return sprintf('%4d-%02d-%02d', $this->getAnnee($periode), $this->getMois($periode), date("t",$this->getMois($periode)));
    }

    public function getPeriodeDebut($campagne) {

        return date('Ym', strtotime(ConfigurationClient::getInstance()->getDateDebutCampagne($campagne)));
    }

    public function getPeriodeFin($campagne) {

        return date('Ym', strtotime(ConfigurationClient::getInstance()->getDateFinCampagne($campagne)));
    }

    public function buildCampagne($periode) {
      
        return ConfigurationClient::getInstance()->buildCampagne($this->buildDate($periode));
    }

    public function buildPeriode($annee, $mois) {

        return sprintf("%04d%02d", $annee, $mois);
    }

    public function buildPeriodeAndVersion($periode, $version) {
        if($version) {
            return sprintf('%s-%s', $periode, $version);
        }

        return $periode;
    }

    public function getAnnee($periode) {

        return preg_replace('/([0-9]{4})([0-9]{2})/', '$1', $periode);
    }

    public function getMois($periode) {

        return preg_replace('/([0-9]{4})([0-9]{2})/', '$2', $periode);
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

    public function findLastByIdentifiantAndCampagne($identifiant, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $drms = $this->viewByIdentifiantAndCampagne($identifiant, $campagne);

        foreach($drms as $id => $drm) {

            return $this->find($id, $hydrate);
        }

        return null;
    }

    public function findMasterByIdentifiantAndPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $drms = $this->viewByIdentifiantPeriode($identifiant, $periode);

        foreach($drms as $id => $drm) {

            return $this->find($id, $hydrate);
        }

        return null;
    }

    public function getMasterVersionOfRectificative($identifiant, $periode, $version_rectificative) {
        $drms = $this->viewByIdentifiantPeriodeAndVersion($identifiant, $periode, $version_rectificative);

        foreach($drms as $id => $drm) {

            return $drm[3];
        }

        return null;
    }
    
    public function findOrCreateByIdentifiantAndPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        if ($obj = $this->findMasterByIdentifiantAndPeriode($identifiant, $periode, $hydrate)) {
         
            return $obj;
        }

        $this->getDRMHistorique($identifiant)->reload();
      
        return $this->createDocByPeriode($identifiant, $periode);
    }

    public function listCampagneByEtablissementId($identifiant) {
      $rows = acCouchdbManager::getClient()
	->group_level(2)
	->startkey(array($identifiant))
	->endkey(array($identifiant, array()))
	->getView("drm", "all")
	->rows;
      $current = ConfigurationClient::getInstance()->getCurrentCampagne();
      $list = array($current => $current);
      foreach($rows as $r) {
	$c = $r->key[1];
	$list[$c] = $c;
      }
      return $list;
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

    public function viewByIdentifiant($identifiant) {
        $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant))
              ->endkey(array($identifiant, array()))
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

    public function viewByIdentifiantAndCampagne($identifiant, $campagne) {
        $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $campagne))
              ->endkey(array($identifiant, $campagne, array()))
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

    protected function viewByIdentifiantPeriode($identifiant, $periode) {
        $campagne = $this->buildCampagne($periode);

        $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $campagne, $periode))
              ->endkey(array($identifiant, $campagne, $periode, array()))
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

    protected function viewByIdentifiantPeriodeAndVersion($identifiant, $periode, $version_rectificative) {
        $campagne = $this->buildCampagne($periode);

        $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $campagne, $periode, $version_rectificative))
              ->endkey(array($identifiant, $campagne, $periode, $this->buildVersion($version_rectificative, 99)))
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
    
    public function getContratsFromProduit($vendeur_identifiant, $produit,$type_transaction)
    {
        if(substr($produit, 0, 1) == "/") {
            $produit = substr($produit, 1);
        }
        $rows = acCouchdbManager::getClient()
              ->startkey(array(VracClient::STATUS_CONTRAT_NONSOLDE, $vendeur_identifiant, $produit,$type_transaction))
              ->endkey(array(VracClient::STATUS_CONTRAT_NONSOLDE, $vendeur_identifiant, $produit,$type_transaction, array()))
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

    public function getDRMHistorique($identifiant) {
        if (!array_key_exists($identifiant, $this->drm_historiques)) {

            $this->drm_historiques[$identifiant] = new DRMHistorique($identifiant);
        }

        return $this->drm_historiques[$identifiant];
    }

    public function createDoc($identifiant, $periode = null) {
        if (!$periode) {
            $periode = $this->getCurrentPeriode();
            $last_drm = $this->getDRMHistorique($identifiant)->getLastDRM();
            if ($last_drm) {
                $periode = $this->getPeriodeSuivante($last_drm->periode);
            }
        }

        return $this->createDocByPeriode($identifiant, $periode);
    }

    public function createDocByPeriode($identifiant, $periode)
    {
        $prev_drm = $this->getDRMHistorique($identifiant)->getPreviousDRM($periode);
        $next_drm = $this->getDRMHistorique($identifiant)->getNextDRM($periode);

        if ($prev_drm) {
            $drm = $prev_drm->generateSuivanteByPeriode($periode);
        } elseif ($next_drm) {
            $drm = $next_drm->generateSuivanteByPeriode($periode);
        } else {
            $drm = new DRM();
            $drm->identifiant = $identifiant;
            $drm->periode = $periode;
        }

        return $drm;
    }
    
    public function getCurrentPeriode() {

        return date('Y-m');
    }

    public function generateVersionCascade($drm) {
        if (!$drm->needNextVersion()) {

            return array();
        }

        $drm_version_suivante = $drm->generateNextVersion();

        if (!$drm_version_suivante) { 
            return array();
        }

        $drm_version_suivante->save();

        return array_merge(array($drm_version_suivante->get('_id')),
                           $this->generateVersionCascade($drm_version_suivante));
    }
    
    public function getLibelleFromId($id) {
        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Orthographe','Date'));
        $origineLibelle = 'DRM de';
        $drmSplited = explode('-', $id);
        $mois = $drmSplited[count($drmSplited)-1];
        $annee = $drmSplited[count($drmSplited)-2];
        $date = $annee.'-'.$mois.'-01';
        $df = format_date($date,'MMMM yyyy','fr_FR');
        return elision($origineLibelle,$df);
    }
}
