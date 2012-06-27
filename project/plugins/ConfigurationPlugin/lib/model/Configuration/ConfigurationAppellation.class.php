<?php
/**
 * Model for ConfigurationAppellation
 *
 */

class ConfigurationAppellation extends BaseConfigurationAppellation {
	
	const TYPE_NOEUD = 'appellation';
    
    // protected function loadAllData() {
    //     parent::loadAllData();
    //     $this->hasCepage();
    //     //$this->hasMillesime();
    // }

    public function getGenre() {

      return $this->getParentNode();
    }

    public function getCertification() {

        return $this->getGenre()->getCertification();
    }

    public function getCepagesChoices(array $exclude_key = array())
    {
        $choices = array();
        foreach ($this->couleurs as $couleur_key => $couleur) {
            foreach ($couleur->cepages as $cepage_key => $cepage) {
                if (!in_array($cepage_key, $exclude_key)) {
                    $choices[$couleur_key][$cepage_key] = $cepage->getLibelle();
                }
            }
        }
        
        return $choices;    
    }

    public function hasCepage() {
        return $this->store('has_cepage', array($this, 'hasCepageStore'));
    }

    public function hasCepageStore() {
        foreach($this->lieux as $lieu) {
            foreach($lieu->couleurs as $couleur) {
                if ($couleur->hasCepage()) {
                    return true;
                }
            }
        }

        return false;
    }
    
    public function getProduits($interpro, $departement) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByAppellation($this->getCertification()->getKey(), $interpro, '', $this->getKey())->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsByAppellation($this->getCertification()->getKey(), $interpro, $departement, $this->getKey())->rows);
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

    public function getLabels($interpro) {

        return $this->getCertification()->getLabels($interpro);
    }

    public function getLibelle() {
      $libelle = $this->_get('libelle');
      if ($libelle)
	     return $libelle;
      return 'Total';
    }
    
    public function setDonneesCsv($datas) {
    	$this->getGenre()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE] : null;
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
