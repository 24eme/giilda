<?php
/**
 * Model for ConfigurationDetail
 *
 */

class ConfigurationDetail extends BaseConfigurationDetail {

  public function getCVO($periode, $interpro) {
    return $this->getParent()->getParent()->getCVO($periode, $interpro);
  }
  
  public function getEntreesSorted($configKey = DRMClient::DRM_CONFIGURATION_KEY_BEFORE_TELEDECLARATION) {
      return $this->getDetailsSorted($this->getEntrees(),$configKey);
  }
  
   public function getSortiesSorted($configKey = DRMClient::DRM_CONFIGURATION_KEY_BEFORE_TELEDECLARATION) {
      return $this->getDetailsSorted($this->getSorties(),$configKey);
  }
  
   public function getDetailsSorted($details,$configKey = DRMClient::DRM_CONFIGURATION_KEY_BEFORE_TELEDECLARATION) {
        $detailsSortedLibelle = array();        
        foreach ($details as $key => $detail) {
            $detailsSortedLibelle[$detail->getLibelle($configKey)] = $detail;
            
        }
        ksort($detailsSortedLibelle);
        $detailsSorted = array();     
        foreach ($detailsSortedLibelle as $keyLibelle => $detail) {
            $detailsSorted[$detail->getKey()] = $detail;
        }
        
        return $detailsSorted;
    }
 	
}