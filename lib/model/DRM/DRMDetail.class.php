<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
  public function getConfig() {
  	
  	return ConfigurationClient::getCurrent()->declaration->detail;
  }

  public function getLibelle($format = "%g% %a% %m% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {

  	return $this->getCepage()->getConfig()->getLibelleFormat($this->labels->toArray(), $format, $label_separator);
  }

  public function getCode($format = "%g%%a%%m%%l%%co%%ce%") {

  	return $this->getCepage()->getConfig()->getCodeFormat($format);
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

  public function getLabelsLibelle($format = "%la%", $label_separator = ", ") {
      
      return $this->getConfig()->getDocument()->formatLabelsLibelle($this->labels->toArray(), $format, $label_separator);
  } 

  
  protected function update($params = array()) {
      parent::update($params);
      
      $this->sorties->vrac = 0;
      foreach ($this->sorties->vrac_details as $vrac_detail)
      {
          $this->sorties->vrac+=$vrac_detail->volume;
      }
      
      $this->sorties->export = 0;
      foreach ($this->sorties->export_details as $export_detail)
      {
          $this->sorties->export+=$export_detail->volume;
      }
      
      $this->sorties->cooperative = 0;
      foreach ($this->sorties->cooperative_details as $cooperative_detail)
      {
          $this->sorties->cooperative+=$cooperative_detail->volume;
      }
      
      
      $this->total_entrees = $this->getTotalByKey('entrees');
      $this->total_sorties = $this->getTotalByKey('sorties');
      
      $this->total = $this->total_debut_mois + $this->total_entrees - $this->total_sorties;
  }
  
  private function getTotalByKey($key) {
  	$sum = 0;
  	foreach ($this->get($key, true) as $k) {
  		if(!is_object($k)) {
  			$sum += $k;
  		}
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

  public function nbToComplete() {
  	return $this->hasMouvementCheck();
  }

  public function nbComplete() {
  	return $this->isComplete();
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
      $produit = $this->getCepage()->getHash();
      if(substr($produit, 0, 1) == "/") {
          $produit = substr($produit, 1);
      }
      $rows = acCouchdbManager::getClient()
          ->startkey(array(VracClient::STATUS_CONTRAT_NONSOLDE, $etablissement, $produit))
            ->endkey(array(VracClient::STATUS_CONTRAT_NONSOLDE, $etablissement, $produit, array()))
            ->getView("vrac", "contratsFromProduit")->rows;
    return count($rows);
  }
  
  public function getContratsVrac() {
  	  $etablissement = $this->getDocument()->identifiant;
  	  return VracClient::getInstance()->retrieveFromEtablissementsAndHash($etablissement, $this->getHash());
  }

  public function isModifiedMother($key) {
    
      return $this->getDocument()->isModifiedMother($this->getHash(), $key);
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
  public function hasStockFinDeMoisDRMPrecedente() {
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

  public function getMouvements() {
    return array_merge(
            $this->getMouvementsByNoeud('entrees', 1),
            $this->getMouvementsByNoeud('sorties', -1)
           );
  }

  public function getMouvementsByNoeud($hash, $coefficient) {
    $mouvements = array();
    foreach($this->get($hash) as $key => $volume) {
      if ($volume instanceof acCouchdbJson) {

        continue;
      }

      if ($this->exist($hash."/".$key."_details")) {
        $mouvements = array_merge($mouvements, $this->get($hash."/".$key."_details")->createMouvements
                ($coefficient));

        continue;
      }

      $mouvement = $this->createMouvement($hash."/".$key, $volume, $coefficient);
      if(!$mouvement){
          continue;
      }
      $mouvements[$mouvement->getMD5Key()] = $mouvement;
    }

    return $mouvements;
  }

  public function createMouvement($hash, $volume, $coefficient) {
    if ($this->getDocument()->hasVersion() && !$this->getDocument()->isModifiedMother($this, $hash)) {

      return false;
    }

    if($this->getDocument()->hasVersion() && $this->getDocument() ->motherExist($this->getHash().'/'.$hash)) {
      $volume = $volume - $this->getDocument()->motherGet($this->getHash().'/'.$hash);
    }

    if(!$volume > 0) {

      return false;
    }

    $mouvement = DRMMouvement::freeInstance($this->getDocument());
    $mouvement->produit_hash = $this->getHash();
    $mouvement->produit_libelle = $this->getLibelle("%g% %a% %m% %l% %co% %ce% %la%");
    $mouvement->type_hash = $hash;
    $mouvement->type_libelle = $this->getConfig()->get($mouvement->type_hash)->getLibelle();
    $mouvement->volume = $coefficient * $volume;
    $mouvement->facture = 0;
    $mouvement->facturable = 0;
    $mouvement->version = 'V 1';
    $mouvement->date_version = date('c');
    
    return $mouvement;
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
