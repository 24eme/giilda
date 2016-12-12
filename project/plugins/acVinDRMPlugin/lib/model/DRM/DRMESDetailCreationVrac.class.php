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
      $etbClient = EtablissementClient::getInstance();
      $vendeurId = $this->getDocument()->getEtablissement()->identifiant;
      $vendeur = $etbClient->find($vendeurId);
      if(!$vendeur){
          throw new sfException("Le vendeur d'id $vendeurId n'existe pas");
      }
      $acheteurId = $this->acheteur;
      $acheteur = $etbClient->find($acheteurId);
      if(!$acheteur){
          throw new sfException("L'acheteur d'id $acheteurId n'existe pas");
      }
      $hash = $this->getDetail()->getCepage()->getHash();
      return VracClient::getInstance()->createContratFromDrm($this->getKey(),$this->identifiant,$vendeur->identifiant,$acheteur->identifiant,$hash,$this->prixhl,$this->volume,$this->date_enlevement,$this->type_contrat);
  }

  public function getDateEnlevement(){
    if(!$this->_get('date_enlevement')){
      return $this->getDocument()->getDate();
    }
    return $this->_get('date_enlevement');
  }

}
