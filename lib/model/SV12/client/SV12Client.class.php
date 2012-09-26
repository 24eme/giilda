<?php

class SV12Client extends acCouchdbClient {
    /**
     *
     * @return DRMClient
     */

    const STATUT_VALIDE = 'valide'; 
    const STATUT_VALIDE_PARTIEL = 'valide_partiel'; 
    const STATUT_BROUILLON = 'brouillon'; 
    
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

    public function buildPeriode($date) {
      
        return str_replace('-', '' , ConfigurationClient::getInstance()->buildCampagne($date));
    }

    public function buildPeriodeAndVersion($periode, $version) {
      if($version) {
        return sprintf('%s-%s', $periode, $version);
      }

      return $periode;
    }

    public function buildCampagne($periode) {
        preg_match('/^([0-9]{4})([0-9]{4})$/', $periode, $matches);

        return sprintf('%d-%d', $matches[1], $matches[2]);
    }
    
    public function createDoc($identifiant, $periode) {
        $sv12 = new Sv12();
        $sv12->identifiant = $identifiant;
        $sv12->periode = $periode;  
        $sv12->storeDeclarant();
        $sv12->storeContrats();
        return $sv12;
    }

    public function findMaster($id_or_identifiant, $periode) {

      $sv12 = SV12AllView::getInstance()->getMasterByEtablissementAndPeriode($id_or_identifiant, $periode);

      if (!$sv12) {
        return;
      }

      return $this->find($sv12->_id);
    }

    public function findMasterRectificative($id_or_identifiant, $periode, $version_rectificative) {

      $sv12 = SV12AllView::getInstance()->getMasterByEtablissementPeriodeAndVersionRectificative($id_or_identifiant, $periode, $version_rectificative);

      if (!$sv12) {
        return;
      }

      return $this->find($sv12->_id);
    }
    
    public function findContratsByEtablissement($identifiant) {   
       return array_merge(VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  VracClient::TYPE_TRANSACTION_MOUTS)->rows,
                          VracClient::getInstance()->retrieveBySoussigneAndType($identifiant,  VracClient::TYPE_TRANSACTION_RAISINS)->rows);
        
    }
    
    public function getLibelleFromId($id) {

        if (!preg_match('/^SV12-[0-9]+-([0-9]{4})([0-9]{4})/', $id, $matches)) {
            
            return $id;
        }

        return sprintf('SV12 de %s-%s', $matches[1], $matches[2]);
    }
}
