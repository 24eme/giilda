<?php
/**
 * Model for ConfigurationLieu
 *
 */

class ConfigurationLieu extends BaseConfigurationLieu {
	
  const TYPE_NOEUD = 'lieu';
  /**
   *
   * @return ConfigurationAppellation
   */
  public function getMention() {
    return $this->getParentNode();
  }

  public function getAppellation() {
    return $this->getMention()->getAppellation();
  }
  
  public function setDonneesCsv($datas) {
    $this->getMention()->setDonneesCsv($datas);
    $this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE] : null;
    $this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_LIEU_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_LIEU_CODE] : null;
  }
  
  public function hasDepartements() {
    return true;
  }
  public function hasDroits() {
    return false;
  }
  public function hasLabels() {
    return false;
  	}
  public function hasDetails() {
    return true;
  }
  public function getTypeNoeud() {
    return self::TYPE_NOEUD;
  }
}