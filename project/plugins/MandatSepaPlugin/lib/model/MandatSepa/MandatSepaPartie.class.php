<?php
class MandatSepaPartie extends acCouchdbDocumentTree {

  public function configureTree() {
     $this->_root_class_name = 'MandatSepa';
     $this->_tree_class_name = 'MandatSepaPartie';
  }

  public function setPartieInformations($partie) {
    if (!($partie instanceof InterfaceMandatSepaPartie)) {
      throw new Exception ("Les parties prenantes du mandat SEPA doivent implémenter InterfaceMandatSepaPartie");
    }
    if ($this->exist('identifiant_ics')) {
      $this->setIdentifiantIcs($partie->getMandatSepaIdentifiant());
    }
    if ($this->exist('identifiant_rum')) {
      $this->setIdentifiantRum($partie->getMandatSepaIdentifiant());
    }
    $this->setNom($partie->getMandatSepaNom());
    $this->setAdresse($partie->getMandatSepaAdresse());
    $this->setCodePostal($partie->getMandatSepaCodePostal());
    $this->setCommune($partie->getMandatSepaCommune());
  }
}
