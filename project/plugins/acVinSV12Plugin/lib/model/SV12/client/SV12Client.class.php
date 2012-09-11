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
    const SV12_VIEWHISTORY_NEGOCIANT_ID = 3;
    const SV12_VIEWHISTORY_NEGOCIANT_NOM = 4;
    const SV12_VIEWHISTORY_NEGOCIANT_CVI = 5;
    const SV12_VIEWHISTORY_NEGOCIANT_COMMUNE = 6;
    const SV12_VIEWHISTORY_STATUT = 7;

    public static function getInstance()
    {
      return acCouchdbManager::getClient("SV12");
    }

    public function getId($identifiant,$periode)
    {
      return 'SV12-'.$identifiant.'-'.$periode;
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
}
