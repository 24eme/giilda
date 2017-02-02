<?php
/**
 * Model for DRMESDetailCreationVrac
 *
 */

class DRMESDetailCreationVrac extends BaseDRMESDetailCreationVrac {

  public function getDetail() {

      return $this->getParent()->getProduitDetail();
  }

  public function getIdentifiantLibelle() {
      return EtablissementClient::getInstance()->find($this->acheteur)->__toString();
  }

  public function getVrac(){
      return VracClient::getInstance()->createContratFromDrmDetails($this);
  }

  public function getDateEnlevement(){
    if(!$this->_get('date_enlevement')){
      return $this->getDocument()->getDate();
    }
    return $this->_get('date_enlevement');
  }

}
