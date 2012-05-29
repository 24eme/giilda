<?php
class ConfigurationProduit
{
	protected static $arborescence = array('certifications', 'appellations', 'lieux', 'couleurs', 'cepages');
	protected static $certifications = array('' => '', 'AOP' => 'AOP', 'IGP' => 'IGP', 'VINSSANSIG' => 'SANS IG', 'LIE' => 'LIE');
	protected static $couleurs = array('' => '', 'Rouge' => 'Rouge', 'Blanc' => 'Blanc', 'Rosé' => 'Rosé');
	protected static $codeCouleurs = array('Rouge' => 'rouge', 'Blanc' => 'blanc', 'Rosé' => 'rose');
	
	protected $datas;
	protected $appellations;
	protected $lieux;
	protected $cepages;
	
	const APPELLATION_KEY = 4;
	const LIEU_KEY = 6;
	const CEPAGE_KEY = 10;
	
	public function __construct($interpro) {
		$this->datas = ConfigurationClient::getInstance()->findProduitsForAdmin($interpro);
		$this->appellations = array('' => '');
		$this->lieux = array('' => '');
		$this->cepages = array('' => '');
		$this->loadDatas();
	}
	
	private function loadDatas() {
    	foreach ($this->datas->rows as $produit) {
    		$hash = $produit->key[7];
    		if ($this->getKey($hash, self::APPELLATION_KEY) != Configuration::DEFAULT_KEY)
    			$this->appellations[$this->getKey($hash, self::APPELLATION_KEY)] = $produit->key[2];
    		if ($this->getKey($hash, self::LIEU_KEY) != Configuration::DEFAULT_KEY)
    			$this->lieux[$this->getKey($hash, self::LIEU_KEY)] = $produit->key[3];
    		if ($this->getKey($hash, self::CEPAGE_KEY) != Configuration::DEFAULT_KEY)
    			$this->cepages[$this->getKey($hash, self::CEPAGE_KEY)] = $produit->key[5];
    	}
    }
    private function getKey($hash, $codeKey) {
    	$hash = explode('/', $hash);
    	return $hash[$codeKey];
    }
    public function getCertifications() {
    	return self::$certifications;
    }
    public function getAppellations() {
    	return $this->appellations;
    }
    public function getLieux() {
    	return $this->lieux;
    }
    public function getCouleurs() {
    	return self::$couleurs;
    }
    public function getCepages() {
    	return $this->cepages;
    }
    public static function getArborescence() {
    	return self::$arborescence;
    }
    public static function getCodeCouleurs() {
    	return self::$codeCouleurs;
    }
}