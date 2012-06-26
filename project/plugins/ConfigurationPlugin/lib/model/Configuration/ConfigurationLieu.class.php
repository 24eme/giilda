<?php
/**
 * Model for ConfigurationLieu
 *
 */

class ConfigurationLieu extends BaseConfigurationLieu {

	const TYPE_NOEUD = 'lieu';

    protected function loadAllData() {
        //parent::loadAllData();
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

    public function getProduits($interpro, $departement, $produits = null) {
      	if (!$produits)
        	$produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByLieu($this->getCertification()->getKey(), $interpro, '', $this->getHash())->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsByLieu($this->getCertification()->getKey(), $interpro, $departement, $this->getHash())->rows);
        }

        foreach($results as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            unset($libelles[1]);
            $libelles[] = '('.$item->key[6].')';
            $produits[$item->key[5]] = $libelles;
        }

        ksort($produits);

        return $produits;
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