<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration {

  const DEFAULT_KEY = 'DEFAUT';

    public function constructId() {
        $this->set('_id', "CONFIGURATION");
    }

    public function getProduitLibelles($hash) {
    	$libelles = $this->store('produits_libelles', array($this, 'getProduitsLibelleAbstract'));
    	if(array_key_exists($hash, $libelles)) {

    		return $libelles[$hash];
    	} else {
    		
    		return null;
    	}
    }

    public function getProduitCodes($hash) {
      $libelles = $this->store('produits_codes', array($this, 'getProduitsCodeAbstract'));
      if(array_key_exists($hash, $libelles)) {

        return $libelles[$hash];
      } else {
        
        return null;
      }
    }

    protected function getProduitsLibelleAbstract() {
    	$results = ConfigurationClient::getInstance()->findProduits();
    	$libelles = array();

    	foreach($results->rows as $item) {
    		$libelles['/'.$item->key[5]] = $item->value;
    	}

    	return $libelles;
    }

    protected function getProduitsCodeAbstract() {
      $results = ConfigurationClient::getInstance()->findProduits();
      $codes = array();

      foreach($results->rows as $item) {
        $codes['/'.$item->key[5]] = $item->key[6];
      }

      return $codes;
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
      if ($previous_libelles)
	$next_libelles = $previous_libelles.' / '.$libelle;
      if (!$parent->exist($obj_id))
	  throw new Exception($next_libelles);
      return array('obj' => $obj_obj, 'next_libelles' => $next_libelles);
    }

    public function identifyNodeProduct($certification, $genre, $appellation, $lieu = 'DEFAUT', $couleur = 'DEFAUT', $cepage = 'DEFAUT', $millesime = null) {
      $hash = $this->identifyProduct($certification, $genre, $appellation, $lieu, $couleur, $cepage, $millesime);
      $rwhash = ' ';
      while ($rwhash != $hash && $rwhash) {
	if ($rwhash != ' ')
	  $hash = $rwhash;
	$rwhash = preg_replace('/[^\/]*\/DEFAUT\/?$/', '', $hash);
      }
      return $hash;
    }

    public function identifyProduct($certification, $genre, $appellation, $lieu = 'DEFAUT', $couleur = 'DEFAUT', $cepage = 'DEFAUT', $millesime = null) {
      try {
	$res = $this->getObjectByLibelle($this->declaration->getCertifications(), $certification);
	$res = $this->getObjectByLibelle($res['obj']->getGenres(), $genre, $res['next_libelles']);
	$res = $this->getObjectByLibelle($res['obj']->getAppellations(), $appellation, $res['next_libelles']);
	$res = $this->getObjectByLibelle($res['obj']->getLieux(), $lieu, $res['next_libelles']);
	$res = $this->getObjectByLibelle($res['obj']->getCouleurs(), $couleur, $res['next_libelles']);
	$res = $this->getObjectByLibelle($res['obj']->getCepages(), $cepage, $res['next_libelles']);
      }catch(Exception $e) {
	throw new sfException("Impossible d'indentifier le produit (".$e->getMessage()." [$certification / $genre / $appellation / $lieu / $couleur / $cepage / $millesime] )");
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
	if ($label == self::normalizeLibelle($l))
	  return $k;
      }
      return false;
    }
    
    public function setLabelCsv($datas) {
    	if ($datas[LabelCsvFile::CSV_LABEL_CODE] && !$this->labels->exist($datas[LabelCsvFile::CSV_LABEL_CODE])) {
    		$this->labels->add($datas[LabelCsvFile::CSV_LABEL_CODE], $datas[LabelCsvFile::CSV_LABEL_LIBELLE]);
    	}
    }

}