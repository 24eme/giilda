<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {
	
	const TYPE_NOEUD = 'certification';

    
    protected function loadAllData() {
        parent::loadAllData();
    }

    public function getLibelles() {

        return array($this->libelle);
    }

    public function getCodes() {
      
        return array($this->code);
    }


    public function getProduits($interpro, $departement) {
        $produits = ConfigurationProduitsView::getInstance()->findProduitsByCertificationByDepartement($interpro, 
        																				  $this->getKey(), 
        																				  '')->rows;

        if ($departement) {
          	$produits = array_merge($produits, ConfigurationProduitsView::getInstance()->findProduitsByCertificationByDepartement($interpro, 
   				 $this->getKey(), 
          		 $departement)->rows);
        }

        return $produits;
    }

    public function formatProduits($interpro, $departement, $format = "%g% %a% %l% %co% %ce%") {

    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduits($interpro, $departement), $format);
    }

    public function getProduitsLieux($interpro, $departement) {

        $produits = ConfigurationProduitsView::getInstance()->findLieuxByCertification($interpro, $this->getKey(), '')->rows;

        if ($departement) {
          $produits = array_merge($produits, ConfigurationProduitsView::getInstance()->findLieuxByCertification($interpro, $this->getKey(), $departement)->rows);
        }

        return $produits;
    }

    public function formatProduitsLieux($interpro, $departement, $format = "%g% %a% %l% %co% %ce%") {

    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduitsLieux($interpro, $departement), $format);
    }

    public function getLabels($interpro) {
        $labels = array();
        $results = ConfigurationProduitsView::getInstance()->findLabelsByCertification($interpro, $this->getKey());
        foreach($results->rows as $item) {
            $labels[$item->key[5]] = $item->value;
        }

        return $labels;
    }

    
    public function setDonneesCsv($datas) {
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE] : null;

    	$this->setDroitDouaneCsv($datas, ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT);
    	$this->setDroitCvoCsv($datas, ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT);
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
  	
  	public function hasUniqProduit($interpro) {
  		if ($interpros = $this->get('interpro')) {
  			if ($interpros->exist($interpro)) {
  				if (count($interpros->get($interpro)->labels) > 0) {

  					return false;
  				}
  			}
  		}
  		$produits = ConfigurationProduitsView::getInstance()->findProduitsByCertification($interpro, $this->getKey());
  		if (count($produits->rows) == 1) {
  			foreach ($produits->rows as $produit) {

  				return $produit->key[7];
  			}
  		} else {

  			return false;
  		}  		
  	}
  	
  	public function hasProduit($interpro, $departement) {
  		$produits = ConfigurationProduitsView::getInstance()->nbProduitsByCertificationByDepartement($interpro, $this->getKey(), $departement);
  		$produitsDefaut = ConfigurationProduitsView::getInstance()->nbProduitsByCertificationByDepartement($interpro, $this->getKey(), '');
  		$total = 0;
  		if (isset($produits->rows[0]) && $produits->rows[0]->value > 0)
  			$total += $produits->rows[0]->value;
  		if (isset($produitsDefaut->rows[0]) && $produitsDefaut->rows[0]->value > 0)
  			$total += $produitsDefaut->rows[0]->value;

  		return ($total > 0);
  	}

}
