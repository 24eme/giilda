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

    protected function getLibellesAbstract() {

        return array($this->getKey() => $this->libelle);
    }

    public function getProduits($interpro, $departement) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro, '')->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro, $departement)->rows);
        }

        foreach($results as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $libelles[] = '('.$item->key[6].')';
            $produits[$item->key[5]] = $libelles;
        }

        ksort($produits);

        return $produits;
    }

    public function getProduitsAppellations($interpro, $departement) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsAppellationsByCertification($this->getKey(), $interpro, '')->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsAppellationsByCertification($this->getKey(), $interpro, $departement)->rows);
        }

        foreach($results as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $libelles[] = '('.$item->key[5].')';
            $produits[$item->key[4]] = $libelles;
        }

        ksort($produits);

        return $produits;
    }

    public function getLabels($interpro) {
        $labels = array();
        $results = ConfigurationClient::getInstance()->findLabelsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $labels[$item->key[3]] = $item->value;
        }

        return $labels;
    }

    
    public function setDonneesCsv($datas) {
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE] : null;
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
