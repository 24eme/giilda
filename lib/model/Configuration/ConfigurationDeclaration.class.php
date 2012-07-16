<?php
/**
 * Model for ConfigurationDeclaration
 *
 */

class ConfigurationDeclaration extends BaseConfigurationDeclaration {
	const TYPE_NOEUD = 'declaration';

    public function getProduits($interpro, $departement = null) {
        $produits = ConfigurationProduitsView::getInstance()->findProduitsByInterpro($interpro)->rows;

        return $produits;
    }

    public function formatProduits($interpro, $departement = null, $format = "%g% %a% %l% %co% %ce%") {

    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduits($interpro), $format);
    }

    public function setDonneesCsv($datas) {
    	
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