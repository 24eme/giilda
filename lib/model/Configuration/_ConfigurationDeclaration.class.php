<?php
/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */

abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree {

	protected $libelles = null;
	protected $codes = null;
  protected $produits_without_view = null;

	protected function loadAllData() {
		parent::loadAllData();
    $this->getProduitsWithoutView();
  }

  abstract public function getChildrenNode();

  public function getParentNode() {
		$parent = $this->getParent()->getParent();
		if ($parent->getKey() == 'declaration') {

			throw new sfException('Noeud racine atteint');
		} else {

			return $this->getParent()->getParent();
		}
	}

	public function getLibelles() {
		if(is_null($this->libelles)) {
			$libelles = $this->getDocument()->getProduitLibelleByHash($this->getHash());
			if ($libelles !== null) {
				$this->libelles = $libelles;
			} else {

				$this->libelles = array_merge($this->getParentNode()->getLibelles(), 
							   	  array($this->libelle));
			}
		}

		return $this->libelles;
	}

	public function getCodes() {
		if(is_null($this->codes)) {
			$codes = $this->getDocument()->getProduitCodeByHash($this->getHash());
			if ($codes !== null) {
				$this->codes = $codes;
			} else {

				$this->codes = array_merge($this->getParentNode()->getCodes(), 
							   	  array($this->code));
			}
		}

		return $this->codes;
	}
        
  public function getProduitsHashByCodeDouane($interpro) {
      $produits = array();
      foreach($this->getChildrenNode() as $key => $item) {
          $produits = array_merge($item->getProduitsHashByCodeDouane($interpro),$produits);
      }

      return $produits;
  }
        
  public function getCodeDouane() {
    if (!$this->_get('code_douane')) {

      return $this->getParentNode()->getCodeDouane();
    }

    return $this->_get('code_douane');
  }    

  public function getCodeProduit() {
    if (!$this->_get('code_produit')) {

      return $this->getParentNode()->getCodeProduit();
    }

    return $this->_get('code_produit');
  }

  public function getCodeComptable() {
    if (!$this->_get('code_comptable')) {

      return $this->getParentNode()->getCodeComptable();
    }    
    
    return $this->_get('code_comptable');
  }
  
  
    public function getDensite(){
      if (!$this->exist('densite') || !$this->_get('densite')) {
        try {
          
          return $this->getParentNode()->getDensite();
        } catch (Exception $e) {

          return null;
        }
      }    
    
      return $this->_get('densite');
    }

	public function getLibelleFormat($labels = array(), $format = "%g% %a% %m% %l% %co% %ce%", $label_separator = ", ") {
    	$libelle = ConfigurationProduitsView::getInstance()->formatLibelles($this->getLibelles(), $format);
    	$libelle = $this->getDocument()->formatLabelsLibelle($labels, $libelle, $label_separator);

    	return trim($libelle);
  	}

  	public function getCodeFormat($format = "%g%%a%%m%%l%%co%%ce%") {

  		return ConfigurationProduitsView::getInstance()->formatCodes($this->getCodes(), $format);
  	}

	public function getDroitCVO($date, $interpro = "INTERPRO-inter-loire") {
    
	  return $this->getDroits($interpro)->get(ConfigurationDroits::CODE_CVO)->getCurrentDroit($date);
	}
	
    public function getDroits($interpro) {
      $droitsable = $this;
      while (!$droitsable->hasDroits()) {
	$droitsable = $droitsable->getParent()->getParent();
      }
      return $droitsable->interpro->getOrAdd($interpro)->droits;
    }

	public function setLabelCsv($datas) {
    	$labels = $this->interpro->getOrAdd('INTERPRO-'.strtolower($datas[LabelCsvFile::CSV_LABEL_INTERPRO]))->labels;
    	$canInsert = true;
    	foreach ($labels as $label) {
    		if ($label == $datas[LabelCsvFile::CSV_LABEL_CODE]) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$labels->add(null, $datas[LabelCsvFile::CSV_LABEL_CODE]);
    	}
    }

    protected function setDepartementCsv($datas) {
    	if (!array_key_exists(ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS, $datas) || !$datas[ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS]) {

    		$this->departements = array();

    		return;
    	}

    	$this->departements = explode(',', $datas[ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS]);
    }

    protected function setDroitDouaneCsv($datas, $code_applicatif) {

    	if (!array_key_exists(ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD, $datas) || $code_applicatif != $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD]) {

    		return;
    	}

    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE]) : null;
    	$code = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE] : null;
    	$libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_LIBELLE] : null;
    	$canInsert = true;
    	foreach ($droits->douane as $droit) {
    		if ($droit->date == $date && $droit->taux == $taux && $droit->code == $code) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$droits = $droits->douane->add();
	    	$droits->date = $date;
	    	$droits->taux = $taux;
	    	$droits->code = $code;
	    	$droits->libelle = $libelle;
    	}
    }
    
    protected function setDroitCvoCsv($datas, $code_applicatif) {

      if (!isset($datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) || !$datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE] || $code_applicatif != $datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) {

    		return;
    	}

    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE]) : null;
    	$code = ConfigurationDroits::CODE_CVO;
    	$libelle = ConfigurationDroits::LIBELLE_CVO;
    	$canInsert = true;
    	foreach ($droits->cvo as $droit) {
    		if ($droit->date == $date && $droit->code == $code) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$droits = $droits->cvo->add();
	    	$droits->date = $date;
	    	$droits->taux = $taux;
	    	$droits->code = $code;
	    	$droits->libelle = $libelle;
    	}
    }
    
    protected function castFloat($float) {
    	return floatval(str_replace(',', '.', $float));
    }

    public function getProduits($interpro, $departement) {
       
      throw new sfException("The method \"getProduits\" is not defined");
    }

    public function getLabels($interpro) {
      
      throw new sfException("The method \"getLabels\" is not defined");
    }

    public function setDonneesCsv($datas) {
      if ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_PRODUIT_NOEUD] == $this->getTypeNoeud()) {
        $this->code_produit = ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_PRODUIT])? $datas[ProduitCsvFile::CSV_PRODUIT_CODE_PRODUIT] : null;
      }

      if ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_COMPTABLE_NOEUD] == $this->getTypeNoeud()) {
        $this->code_comptable = ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_COMPTABLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CODE_COMPTABLE] : null;
      }

      if ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_DOUANE_NOEUD] == $this->getTypeNoeud()) {
        $this->code_douane = ($datas[ProduitCsvFile::CSV_PRODUIT_CODE_DOUANE])? $datas[ProduitCsvFile::CSV_PRODUIT_CODE_DOUANE] : null;
      }      
    }

  	public abstract function getTypeNoeud();

  	public function getDetailConfiguration() {
  		try {
			$parent_node = $this->getParentNode();
		} catch (Exception $e) {
			return $this->getDetail();;
		}

  		$details = $this->getParentNode()->getDetailConfiguration();
  		if ($this->exist('detail')) {
  			foreach ($this->detail as $type => $detail) {
  				foreach ($detail as $noeud => $droits) {
  					if ($droits->readable !== null)
  						$details->get($type)->get($noeud)->readable = $droits->readable;
  					if ($droits->writable !== null)
  						$details->get($type)->get($noeud)->writable = $droits->writable;
  				}
  			}
  		}
  		return $details;
  	}

    public function getProduitsWithoutView() {
      if(is_null($this->produits_without_view)) {
        $this->produits_without_view = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $this->produits_without_view = array_merge($this->produits_without_view, $item->getProduitsWithoutView());
        }
      }

      return $this->produits_without_view;
    }

    public function getKeys($noeud) {
      if($noeud == $this->getTypeNoeud()) {

        return array($this->getKey() => $this);
      }

      $items = array();
      foreach($this->getChildrenNode() as $key => $item) {
          $items = array_merge($items, $item->getKeys($noeud));
      }

      return $items;
    }

    public function addInterpro($interpro) 
    {
      if ($this->exist('interpro')) {
        $this->interpro->getOrAdd($interpro);
      }
      return $this->getParentNode()->addInterpro($interpro);
    }

    public function hasDepartements() {
        return false;
    }

    public function hasDroits() {
        return true;
    }

    public function hasLabels() {
        return false;
    }

    public function hasDetails() {
        return false;
    }

    public function hasDroit($type) {
      if(!$this->hasDroits()) {

        return false;
      }
      
      if($type == ConfigurationDroits::DROIT_CVO){
        return true;
      }

      return false;
    }

    public function hasCodes() {
        return false;
    }
}