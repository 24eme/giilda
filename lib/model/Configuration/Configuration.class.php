<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration {

  	const DEFAULT_KEY = 'DEFAUT';
  
	/*
	 * VRAC CONST
	 */
    const TYPE_CONTRAT_SPOT = 'spot';
    const TYPE_CONTRAT_PLURIANNUEL = 'pluriannuel';

    const CVO_NATURE_MARCHE_DEFINITIF = 'marche_definitif';
    const CVO_NATURE_COMPENSATION = 'compensation';
    const CVO_NATURE_NON_FINANCIERE = 'non_financiere';
    const CVO_NATURE_VINAIGRERIE = 'vinaigrerie';
    
    const STATUT_CONTRAT_SOLDE = 'SOLDE';
    const STATUT_CONTRAT_ANNULE = 'ANNULE';
    const STATUT_CONTRAT_NONSOLDE = 'NONSOLDE';

    protected $produits_libelle = null;
    protected $produits_code = null;

    public function constructId() {
        $this->set('_id', "CONFIGURATION");
    }

    public function getProduits() {
      	
      	return ConfigurationProduitsView::getInstance()->findProduits()->rows;
    }

    public function formatProduits($format = "%g% %a% %m% %l% %co% %ce%") {

    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduits(), $format);
    }

    public function getProduitLibelleByHash($hash) {
    	if(is_null($this->produits_libelle)) {
    		$this->produits_libelle = ConfigurationProduitsView::getInstance()->getProduitsLibelles();
    	}

    	return (array_key_exists($hash, $this->produits_libelle)) ? $this->produits_libelle[$hash] : null;
    }

    public function getProduitCodeByHash($hash) {
    	if(is_null($this->produits_code)) {
    		$this->produits_code = ConfigurationProduitsView::getInstance()->getProduitsCodes();
    	}

    	return (array_key_exists($hash, $this->produits_code)) ? $this->produits_code[$hash] : null;
    }

    private static function normalizeLibelle($libelle) {
      	$libelle = str_ireplace('SAINT-', 'saint ', $libelle);
      	$libelle = preg_replace('/&nbsp;/', ' ', strtolower($libelle));
      	if (!preg_match('/&[^;]+;/', $libelle)) {
			$libelle = html_entity_decode(preg_replace('/&([^;#])[^;]*;/', '\1', htmlentities($libelle, ENT_NOQUOTES, 'UTF-8')));
     	}
      	$libelle = str_replace(array('é', 'è', 'ê'), 'e', $libelle);
      	$libelle = preg_replace('/[^a-z ]/', '', preg_replace('/  */', ' ', preg_replace('/&([a-z])[^;]+;/i', '\1', $libelle)));
     	$libelle = preg_replace('/^\s+/', '', preg_replace('/\s+$/', '', $libelle));

      	return $libelle;
    }

    private function getObjectByLibelle($parent, $libelle, $previous_libelles = null) {
      	$libelle = ($libelle) ? self::normalizeLibelle($libelle) : 'DEFAUT';
      	$obj_id = 'DEFAUT';
      	foreach ( $parent as $obj_key => $obj_obj) {
			if ($libelle == self::normalizeLibelle($obj_obj->getLibelle())) {
  				$obj_id = $obj_key;

			  	break;
			}
      	}
      	$next_libelles = $libelle;
      	if ($previous_libelles) {
			$next_libelles = $previous_libelles.' / '.$libelle;
      	}
      	if (!$parent->exist($obj_id)) {
		  	throw new Exception($next_libelles);
		}

      	return array('obj' => $obj_obj, 'next_libelles' => $next_libelles);
    }

    public function identifyNodeProduct($certification, $genre, $appellation, $mention, $lieu = 'DEFAUT', $couleur = 'DEFAUT', $cepage = 'DEFAUT', $millesime = null) {
      	$hash = $this->identifyProduct($certification, $genre, $appellation, $mention, $lieu, $couleur, $cepage, $millesime);
      	$rwhash = ' ';
      	while ($rwhash != $hash && $rwhash) {
			if ($rwhash != ' ') {
	  			$hash = $rwhash;
	  		}
			$rwhash = preg_replace('/[^\/]*\/DEFAUT\/?$/', '', $hash);
      	}

      	return $hash;
    }

    public function identifyProduct($certification, $genre, $appellation, $mention = 'DEFAULT', $lieu = 'DEFAUT', $couleur = 'DEFAUT', $cepage = 'DEFAUT', $millesime = null) {
      	try {
		$res = $this->getObjectByLibelle($this->declaration->getCertifications(), $certification);
		$res = $this->getObjectByLibelle($res['obj']->getGenres(), $genre, $res['next_libelles']);
		$res = $this->getObjectByLibelle($res['obj']->getAppellations(), $appellation, $res['next_libelles']);
		$res = $this->getObjectByLibelle($res['obj']->getMentions(), $mention, $res['next_libelles']);
		$res = $this->getObjectByLibelle($res['obj']->getLieux(), $lieu, $res['next_libelles']);
		$res = $this->getObjectByLibelle($res['obj']->getCouleurs(), $couleur, $res['next_libelles']);
		$res = $this->getObjectByLibelle($res['obj']->getCepages(), $cepage, $res['next_libelles']);
		} catch(Exception $e) {		
			throw new sfException("Impossible d'indentifier le produit (".$e->getMessage()." [$certification / $genre / $appellation / $mention / $lieu / $couleur / $cepage / $millesime] )");
		}

		return $res['obj']->getHash();
    }

    public function identifyLabels($labels, $separateur = '|') {
      	$label_keys = array();
      	foreach(explode($separateur, $labels) as $l) {
			if ($k = $this->identifyLabel($l)) {
				$label_keys[] = $k;
			}
      	}
      
      	return $label_keys;
    }

    public function identifyLabel($label) {
      	$label = self::normalizeLibelle($label);
      	foreach ($this->labels as $k => $l) {
			if ($label == self::normalizeLibelle($l)) {
	  			
	  			return $k;
	  		}
      	}
      	
      	return false;
    }
    
    public function setLabelCsv($datas) {
    	if ($datas[LabelCsvFile::CSV_LABEL_CODE] && !$this->labels->exist($datas[LabelCsvFile::CSV_LABEL_CODE])) {
    		$this->labels->add($datas[LabelCsvFile::CSV_LABEL_CODE], $datas[LabelCsvFile::CSV_LABEL_LIBELLE]);
    	}
    }
  
   public function getMillesimes() {
        $lastMillesime =  date('Y');
        $result = array();
        for($i=$lastMillesime;$i>=1991;$i--) $result[$i] = $i;

        return $result;
    }


    public function formatLabelsLibelle($labels, $format = "%la%", $separator = ", ") {
      $libelles = $this->getLabelsLibelles($labels);
      
      return str_replace("%la%", implode($separator, $libelles), $format);
    }

    public function getLabelsLibelles($labels) {
        $libelles = array(); 
        foreach($labels as $key) {
            $libelles[$key] = ConfigurationClient::getCurrent()->labels[$key];
        }

        return $libelles;
    }
    
    /*
     * FONCTIONS VRAC
     */

    public function getVracNaturesCvo() {

    	return array(self::CVO_NATURE_MARCHE_DEFINITIF => 'Marché définitif',
                     self::CVO_NATURE_COMPENSATION => 'Compensation',
                     self::CVO_NATURE_NON_FINANCIERE => 'Non financière',
                     self::CVO_NATURE_VINAIGRERIE => 'Vinaigrerie');
    }
    public function getVracRepartitionsCvo() {

    	return array('50' => '50/50',
                     '100' => '100% viticulteur',
                     '0' => 'Vinaigrerie');
    }
    public function getVracTypesContrat() {

    	return array(self::TYPE_CONTRAT_SPOT => 'Spot',
                     self::TYPE_CONTRAT_PLURIANNUEL => 'Pluriannuel');
    }
    public function getVracStatutAnnule() {

    	return self::STATUT_CONTRAT_ANNULE;
    }
    public function getVracStatutNonSolde() {

    	return self::STATUT_CONTRAT_NONSOLDE;
    }
    public function getVracStatutSolde() {

    	return self::STATUT_CONTRAT_SOLDE;	
    }

}