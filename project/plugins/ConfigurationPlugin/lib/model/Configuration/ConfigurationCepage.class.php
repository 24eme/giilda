<?php
/**
 * Model for ConfigurationCepage
 *
 */

class ConfigurationCepage extends BaseConfigurationCepage {
	
	const TYPE_NOEUD = 'cepage';

    public function getAppellation() {

      return $this->getCouleur()->getLieu()->getAppellation();
    }

    public function getGenre() {

      return $this->getAppellation()->getGenre();
    }

    public function getCouleur() {
    	return $this->getParentNode();
    }
    
    public function setDonneesCsv($datas) {
    	$this->getCouleur()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE] : null;
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