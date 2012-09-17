<?php

class SV12Client extends acCouchdbClient {
    /**
     *
     * @return DRMClient
     */
    
    const SV12_STATUT_VALIDE = 'valide'; 
    const SV12_STATUT_BROUILLON = 'brouillon'; 
    
    const SV12_VIEWHISTORY_ID = 0;
    const SV12_VIEWHISTORY_DATESAISIE = 1;    
    const SV12_VIEWHISTORY_PERIODE = 2;
    const SV12_VIEWHISTORY_VERSION = 3;     
    const SV12_VIEWHISTORY_NEGOCIANT_ID = 4;
    const SV12_VIEWHISTORY_NEGOCIANT_NOM = 5;
    const SV12_VIEWHISTORY_NEGOCIANT_CVI = 6;
    const SV12_VIEWHISTORY_NEGOCIANT_COMMUNE = 7;
    const SV12_VIEWHISTORY_STATUT = 8;

    public static function getInstance()
    {
      return acCouchdbManager::getClient("SV12");
    }

    public function buildId($identifiant, $periode, $version = null) {

      return 'SV12-'.$identifiant.'-'.$this->buildPeriodeAndVersion($periode, $version);
    }

    public function buildPeriodeAndVersion($periode, $version) {
      if($version) {
        return sprintf('%s-%s', $periode, $version);
      }

      return $periode;
    }

    public function buildCampagne($periode) {
      
        return '2012-2013';
    }
    
    public function createDoc($identifiant, $annee = null) {
        $sv12 = new Sv12();
        if (!$annee) $annee = date('Y');
        $sv12->identifiant = $identifiant;
        $sv12->periode = $annee;  
        $sv12->storeDeclarant();
        $sv12->storeContrats();
        return $sv12;
    }
    
    public function retrieveContratsByEtablissement($identifiant) {   
       return array_merge(VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  VracClient::TYPE_TRANSACTION_MOUTS)->rows,
                          VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  VracClient::TYPE_TRANSACTION_RAISINS)->rows);
        
    }
    
    public function retrieveLastDocs($limit = 300) {
        $rows = $this->startkey(array(SV12Client::SV12_STATUT_BROUILLON))
                     ->endkey(array(SV12Client::SV12_STATUT_BROUILLON, array()))
                     ->limit($limit) //FIXME :  ->descending(true);
                     ->getView('sv12', 'history')->rows;

        $drms = array();

        foreach($rows as $row) {
          $drms[$row->id] = $row->value;
        }
        
        krsort($drms);
        
        return $drms;
    }

    public function retrieveByEtablissement($identifiant) {
        $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant))
              ->endkey(array($identifiant, array()))
              ->getView("sv12", "all")
              ->rows;
      
        $drms = array();

        foreach($rows as $row) {
          $drms[$row->id] = $row->value;
        }
        
        krsort($drms);
        
        return $drms;
    }
    
    public function viewByIdentifiantAndCampagne($identifiant, $campagne) {
      $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $campagne))
              ->endkey(array($identifiant, $campagne, array()))
              ->getView("sv12", "all")
              ->rows;
      
      $drms = array();

      foreach($rows as $row) {
        $drms[$row->id] = $row->key;
      }
      
      krsort($drms);
      
      return $drms;
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

    protected function viewByIdentifiantPeriode($identifiant, $periode) {
        $rows = acCouchdbManager::getClient()
              ->startkey(array($identifiant, $periode))
                ->endkey(array($identifiant, $periode, array()))
                ->getView("sv12", "all")
                ->rows;
        
        $drms = array();

        foreach($rows as $row) {
          $drms[$row->id] = $row->key;
        }
        
        krsort($drms);
        
        return $drms;
    }
    
    protected function viewByIdentifiantPeriodeAndVersion($identifiant, $periode, $version_rectificative) {
      $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $periode, $version_rectificative))
              ->endkey(array($identifiant, $periode, $this->buildVersion($version_rectificative, 99)))
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
    
    public function getLibelleFromIdSV12($id) {
        $origineLibelle = 'SV12 de ';
        $drmSplited = explode('-', $id);
        $annee = $drmSplited[count($drmSplited)-1];
        return $origineLibelle.$annee;
    }
}
