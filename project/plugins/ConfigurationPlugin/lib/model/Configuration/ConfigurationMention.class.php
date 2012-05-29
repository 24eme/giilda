<?php
/**
 * Model for ConfigurationMention
 *
 */

class ConfigurationMention extends BaseConfigurationMention {
    public function getAppellation() {
        return $this->getParentNode();
    }

  public function setDonneesCsv($datas) {
    $this->getAppellation()->setDonneesCsv($datas);
    $this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_MENTION_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_MENTION_LIBELLE] : null;
    $this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_MENTION_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_MENTION_CODE] : null;
  }
  
  public function hasDepartements() {
    return false;
  }
  public function hasDroits() {
    return false;
  }
  public function hasLabels() {
    return false;
  	}
  public function hasDetails() {
    return false;
  }
  public function getTypeNoeud() {
    return self::TYPE_NOEUD;
  }

}