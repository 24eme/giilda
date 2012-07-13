<?php
/**
 * Model for ConfigurationGenre
 *
 */

class ConfigurationGenre extends BaseConfigurationGenre {
	const TYPE_NOEUD = 'genre';
	public function getCertification() {

		return $this->getParentNode();
	}
    
    public function setDonneesCsv($datas) {
    	$this->getCertification()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_GENRE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_GENRE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_GENRE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_GENRE_CODE] : null;
    	
    	$this->setDroitDouaneCsv($datas, ProduitCsvFile::CSV_PRODUIT_GENRE_CODE_APPLICATIF_DROIT);
    	$this->setDroitCvoCsv($datas, ProduitCsvFile::CSV_PRODUIT_GENRE_CODE_APPLICATIF_DROIT);
    }
    
  	public function hasDepartements() {
  		return true;
  	}
  	public function hasDroits() {
  		return true;
  	}
  	public function hasLabels() {
  		return true;
  	}
  	public function hasDetails() {
  		return true;
  	}
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
}