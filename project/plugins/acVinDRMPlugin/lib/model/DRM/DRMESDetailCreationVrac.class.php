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
      return VracClient::getInstance()->createContratFromDrm($this->identifiant);
  }

}
