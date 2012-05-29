<?php
/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */

abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree {

	protected function loadAllData() {
		parent::loadAllData();
    }

	public function getLibelles() {
		return $this->store('libelles', array($this, 'getLibellesAbstract'));
	}

	protected function getLibellesAbstract() {
		$libelle = $this->getDocument()->getProduitLibelles($this->getHash());
		if ($libelle !== null) {

			return $libelle;
		} else {

			return array_merge($this->getParentNode()->getLibelles(), 
						   array($this->libelle));
		}
	}

	public function getCodes() {

		return $this->store('codes', array($this, 'getCodesAbstract'));
	}

	protected function getCodesAbstract() {
		$codes = $this->getDocument()->getProduitCodes($this->getHash());
		if ($codes !== null) {

			return $codes;
		} else {

			return $this->getParentNode()->getCodes().$this->getCode();
		}
	}

	public function getParentNode() {
		$parent = $this->getParent()->getParent();
		if ($parent->getKey() == 'declaration') {
			throw new sfException('Noeud racine atteint');
		} else {
			return $this->getParent()->getParent();
		}
	}

    public function getDroits($interpro) {
      return $this->interpro->getOrAdd($interpro)->droits;
    }

	public function setLabelCsv($datas) {
		try {
			$parent_node = $this->getParentNode();
		} catch (Exception $e) {
			return;
		}

    	$parent_node->setLabelCsv($datas);
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

    protected function setDroitDouaneCsv($datas) {
    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE]) : null;
    	$code = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE] : null;
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
    	}
    }
    
    protected function setDroitCvoCsv($datas) {
    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE]) : null;
    	$code = ConfigurationDroits::CODE_CVO;
    	$canInsert = true;
    	foreach ($droits->cvo as $droit) {
    		if ($droit->date == $date && $droit->taux == $taux && $droit->code == $code) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$droits = $droits->cvo->add();
	    	$droits->date = $date;
	    	$droits->taux = $taux;
	    	$droits->code = $code;
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

    public abstract function setDonneesCsv($datas);
  	public abstract function hasDepartements();
 	  public abstract function hasDroits();
  	public abstract function hasLabels();
  	public abstract function hasDetails();
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
}