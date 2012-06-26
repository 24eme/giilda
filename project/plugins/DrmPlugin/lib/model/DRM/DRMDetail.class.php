<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
    
    public function getLibelles() {

        return $this->getCepage()->getConfig()->getLibelles();
    }

    public function getCodes() {

        return $this->getCepage()->getConfig()->getCodes();
    }
    
    public function getConfig() {
    	return ConfigurationClient::getCurrent()->declaration->certifications->get($this->getCertification()->getKey())->detail;
    }
    
    /**
     *
     * @return DRMCepage
     */
    public function getCepage() {

        return $this->getParent()->getParent();
    }

    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {

        return $this->getCepage()->getCouleur();
    }

    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {

        return $this->getCouleur()->getLieu();
    }

    /**
     *
     * @return DRMMention
     */
    public function getMention() {

        return $this->getLieu()->getMention();
    }

    /**
     *
     * @return DRMAppellation
     */
    public function getAppellation() {

        return $this->getLieu()->getAppellation();
    }


    public function getGenre() {
      return $this->getAppellation()->getGenre();
    }


    public function getCertification() {
      return $this->getGenre()->getCertification();
    }

    public function getLabelKeyFromValues($values) {
        
    }
    
    public function getLabelKeyString() {
      if ($this->labels) {
	return implode('|', $this->labels->toArray());
      }
      return '';
    }
    public function getLabelKey() {
    	$key = null;
    	if ($this->labels) {
    		$key = implode('-', $this->labels->toArray());
    	}
    	return ($key) ? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelLibellesString() {
      return implode('|', $this->getLabelLibelles());
    }
    
    public function getLabelLibelles() {
        $libelles = array(); 
        foreach($this->labels as $key) {
            $libelles[] = ConfigurationClient::getCurrent()->labels[$key];
        }

        return $libelles;
    }
    
    protected function update($params = array()) {
        parent::update($params);
        $this->total_entrees = $this->getTotalByKey('entrees');
        $this->total_sorties = $this->getTotalByKey('sorties');
        $this->total = $this->total_debut_mois + $this->total_entrees - $this->total_sorties;
    }
    
    private function getTotalByKey($key) {
    	$sum = 0;
    	foreach ($this->get($key, true) as $k) {
    		$sum += $k;
    	}
    	return $sum;
    }

    public function getTotalDebutMois() {
        if (is_null($this->_get('total_debut_mois'))) {
            return 0;
        } else {
            return $this->_get('total_debut_mois');
        }
    }

    public function isComplete() {
        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }
    
    public function getIdentifiantHTML() {
      return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
    }	
    
    public function addVrac($contrat_numero, $volume) {
      $contratVrac = $this->vrac->add($contrat_numero."");
      $contratVrac->volume = $volume*1 ;
    }

    public function hasContratVrac() {
      $etablissement = $this->getDocument()->identifiant;
      foreach (VracClient::getInstance()->getAll() as $contrat) {
      	if ($contrat->actif && $contrat->etablissement == $etablissement && (strpos($this->getHash(), $contrat->produit) !== false) && !$this->vrac->exist($contrat->numero)) {
      	  return true;
      	}
      }
      return false;
    }
    
    public function getContratsVrac() {
    	$etablissement = $this->getDocument()->identifiant;
    	return VracClient::getInstance()->retrieveFromEtablissementsAndHash($etablissement, $this->getHash());
    }

    public function isModifiedMasterDRM($key) {
      
      return $this->getDocument()->isModifiedMasterDRM($this->getHash(), $key);
    }


    public function getDroitVolume($type) {
      return $this->sommeLignes(DRMDroits::getDroitSorties()) - $this->sommeLignes(DRMDroits::getDroitEntrees());
    }

    public function getDroit($type) {
      return $this->getAppellation()->getDroit($type);
    }

    protected function init($params = array()) {
      parent::init($params);
      
      $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
	
      $this->total_debut_mois = ($keepStock)? $this->total : null;
      $this->total_entrees = null;
      $this->total_sorties = null;
      $this->total = null;
      
    }

    public function sommeLignes($lines) {
      $sum = 0;
      foreach($lines as $line) {
	$sum += $this->get($line);
      }
      return $sum;
    }
    public function hasStockFinDeMoisDrmPrecedente() {
    	$result = false;
    	$drmPrecedente = $this->getDocument()->getPrecedente();
    	if (!$drmPrecedente->isNew()) {
    		if ($drmPrecedente->exist($this->getHash())) {
    			if ($drmPrecedente->get($this->getHash())->total) {
    				$result = true;
    			}
    		}
    	}
    	return $result;
    }
	/*
	 * Fonction calculée
	 */
    public function hasMouvement() {

        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }

    public function hasStockEpuise() {

        return $this->total_debut_mois == 0 && !$this->hasMouvement();
    }

    public function hasMouvementCheck() {

        return !$this->pas_de_mouvement_check;
    }

    public function cascadingDelete() {
    	$cepage = $this->getCepage();
    	$couleur = $this->getCouleur();
    	$lieu = $this->getLieu();
    	$mention = $this->getMention();
    	$appellation = $this->getAppellation();
    	$genre = $this->getGenre();
    	$certification = $this->getCertification();
    	$objectToDelete = $this;
    	if ($cepage->details->count() == 1 && $cepage->details->exist($this->getKey())) {
    		$objectToDelete = $cepage;
    		if ($couleur->cepages->count() == 1 && $couleur->cepages->exist($cepage->getKey())) {
    			$objectToDelete = $couleur;
    			if ($lieu->couleurs->count() == 1 && $lieu->couleurs->exist($couleur->getKey())) {
    				$objectToDelete = $lieu;
    				if ($mention->lieux->count() == 1 && $mention->lieux->exist($lieu->getKey())) {
    					$objectToDelete = $mention;
	    				if ($appellation->mentions->count() == 1 && $appellation->mentions->exist($mention->getKey())) {
	    					$objectToDelete = $appellation;
							if ($genre->appellations->count() == 1 && $genre->appellations->exist($appellation->getKey())) {
	    						$objectToDelete = $genre;
								if ($certification->genres->count() == 1 && $certification->genres->exist($genre->getKey())) {
	    							$objectToDelete = $certification;
								}
							}
	    				}
    				}
    			}
    		}
    	}
    	return $objectToDelete;
    }
}