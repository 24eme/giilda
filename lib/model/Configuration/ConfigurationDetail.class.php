<?php
/**
 * Model for ConfigurationDetail
 *
 */

class ConfigurationDetail extends BaseConfigurationDetail {

  public function getCVO($periode, $interpro) {
    return $this->getParent()->getParent()->getCVO($periode, $interpro);
  }
  
  public function getEntreesSorted() {
      return $this->getDetailsSorted($this->getEntrees());
  }
  
   public function getSortiesSorted() {
      return $this->getDetailsSorted($this->getSorties());
  }
  
   public function getDetailsSorted($details) {
        $detailsSortedLibelle = array();        
        foreach ($details as $key => $detail) {
            $detailsSortedLibelle[$detail->getLibelle()] = $detail;
            
        }
        ksort($detailsSortedLibelle);
        $detailsSorted = array();     
        foreach ($detailsSortedLibelle as $keyLibelle => $detail) {
            $detailsSorted[$detail->getKey()] = $detail;
        }
        
        return $detailsSorted;
    }
 	
}