<?php
/**
 * Model for ConfigurationLieu
 *
 */

class ConfigurationLieu extends BaseConfigurationLieu {
	
	  const TYPE_NOEUD = 'lieu';

    protected function loadAllData() {
        parent::loadAllData();
        $this->hasCepage();
    }

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

    public function getCertification() {

        return $this->getAppellation()->getCertification();
    }
    
    public function getLabels($interpro) {

        return $this->getCertification()->getLabels($interpro);
    }

    public function hasCepage() {
        return $this->store('has_cepage', array($this, 'hasCepageStore'));
    }

    public function hasCepageStore() {
        foreach($this->couleurs as $couleur) {
            if ($couleur->hasCepage()) {
                return true;
            }
        }
        
        return false;
    }

    public function getProduits($interpro, $departement) {
        $produits = ConfigurationProduitsView::getInstance()->findProduitsByLieu($interpro, 
        																		$this->getCertification()->getKey(), 
        																		'', 
        																		$this->getHash())->rows;

        if ($departement) {
          $produits = array_merge($produits, 
          						 ConfigurationProduitsView::getInstance()->findProduitsByLieu($interpro, 
          																				      $this->getCertification()->getKey(), 
          																				      $departement, 
          																				      $this->getHash())->rows);
        }

        return $produits;
    }
    
    public function formatProduits($interpro, $departement, $format = "%co% %ce%") {

    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduits($interpro, $departement), $format);
    }

    public function setDonneesCsv($datas) {
    	$this->getAppellation()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_LIEU_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_LIEU_CODE] : null;
    	
    	$this->setDepartementCsv($datas);
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