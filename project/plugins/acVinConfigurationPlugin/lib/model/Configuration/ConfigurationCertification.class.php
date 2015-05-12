<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {
	
	  const TYPE_NOEUD = 'certification';
    
    public function getChildrenNode() {

      return $this->genres;
    }

    public function getLibelles() {

        return array($this->libelle);
    }

    public function getCodes() {
      
        return array($this->code);
    }

    public function getCodeProduit() {

      return $this->_get('code_produit');
    }

    public function getCodeComptable() {

      return $this->_get('code_comptable');
    }
      
    public function getCodeDouane() {
        return $this->_get('code_douane');
    }

    public function getLabels($interpro) {
        
        return $this->getDocument()->labels;
    }

    
    public function setDonneesCsv($datas) {
      parent::setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE] : null;

    	$this->setDroitDouaneCsv($datas, ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT);
    	$this->setDroitCvoCsv($datas, ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT);
    }
  	
  	public function hasUniqProduit($interpro) {
  		return count($this->getProduits($interpro, $departement)) == 1;
  	}
  	
  	public function hasProduit($interpro, $departement) {

  		return count($this->getProduits($interpro, $departement)) > 0;
  	}

    public function addInterpro($interpro) 
    {
      return null;  
    }

    public function getTypeNoeud() {
        
        return self::TYPE_NOEUD;
    }

}
