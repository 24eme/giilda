<?php
/**
 * Inheritance tree class _ConfigurationDeclaration
 *
 */

abstract class _ConfigurationDeclaration extends acCouchdbDocumentTree {

	protected $libelles = null;
	protected $codes = null;

	protected function loadAllData() {
		parent::loadAllData();
  	}

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

	public function getLibelleFormat($labels = array(), $format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {
    	$libelle = ConfigurationProduitsView::getInstance()->formatLibelles($this->getLibelles(), $format);
    	$libelle = $this->getDocument()->formatLabelsLibelle($labels, $libelle, $label_separator);

    	return $libelle;
  	}

  	public function getCodeFormat($format = "%g%%a%%l%%co%%ce%") {

  		return ConfigurationProduitsView::getInstance()->formatCodes($this->getCodes(), $format);
  	}

    public function getDroits($interpro) {

      return $this->interpro->getOrAdd($interpro)->droits;
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

    	if (!array_key_exists(ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD, $datas) || $code_applicatif != $datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) {

    		return;
    	}

    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE]) : null;
    	$code = ConfigurationDroits::CODE_CVO;
    	$libelle = ConfigurationDroits::LIBELLE_CVO;
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