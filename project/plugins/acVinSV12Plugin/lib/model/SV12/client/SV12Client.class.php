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
    const SV12_VIEWHISTORY_NEGOCIANT_NOM = 3;
    const SV12_VIEWHISTORY_NEGOCIANT_CVI = 4;
    const SV12_VIEWHISTORY_NEGOCIANT_COMMUNE = 5;

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
        $sv12->negociant_identifiant = $identifiant;
        $sv12->periode = $annee;        
        $sv12->identifiant = $identifiant.'-'.$annee;
        $nego = EtablissementClient::getInstance()->findByIdentifiant($identifiant);
        $sv12->negociant->nom = $nego->nom;
        $sv12->negociant->cvi = $nego->cvi;
        $sv12->negociant->num_accise = $nego->no_accises;
        $sv12->negociant->num_tva_intracomm = $nego->no_tva_intracommunautaire;
        $sv12->negociant->adresse = $nego->siege->adresse;        
        $sv12->negociant->commune = $nego->siege->commune;
        $sv12->negociant->code_postal = $nego->siege->code_postal;
        return $sv12;
    }
    
    public function retrieveContratsByEtablissement($identifiant) {   
       return array_merge(VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  VracClient::TYPE_TRANSACTION_MOUTS)->rows,
                          VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  VracClient::TYPE_TRANSACTION_RAISINS)->rows);
        
    }
    
    public function retrieveLastDocs($limit = 300) {
        $sv12HistoryQuery = $this->startkey(array(SV12Client::SV12_STATUT_BROUILLON))->endkey(array(SV12Client::SV12_STATUT_BROUILLON,array()));
        $sv12HistoryQuery = $sv12HistoryQuery->limit($limit);//FIXME :  ->descending(true);
        return $sv12HistoryQuery->getView('sv12', 'history');
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
