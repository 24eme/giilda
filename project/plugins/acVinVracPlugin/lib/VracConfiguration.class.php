<?php
class VracConfiguration
{
	private static $_instance = null;
	protected $configuration;
	
	public static function getInstance()
	{
		if(is_null(self::$_instance)) {
			self::$_instance = new VracConfiguration();
		}
		return self::$_instance;
	}
	
	public function __construct() 
	{
		$this->configuration = sfConfig::get('vrac_configuration_vrac', array());
	}
	
	public function getTransactions()
	{
		return $this->configuration['transactions'];
	}
	
	public function getContenances()
	{
		return $this->configuration['contenances'];
	}
	
	public function getDelaisPaiement()
	{
		return $this->configuration['delais_paiement'];
	}
	
	public function getMoyensPaiement()
	{
		return $this->configuration['moyens_paiement'];
	}
	
	public function getRepartitionCourtage()
	{
		return $this->configuration['repartition_courtage'];
	}
	
	public function getTva()
	{
		return $this->configuration['tva'];
	}
	
	public function getCategories()
	{
		return $this->configuration['categories'];
	}
	
	public function getEtapes()
	{
		return $this->configuration['etapes'];
	}
	
	public function getChamps($etape)
	{
		return $this->configuration['champs'][$etape];
	}
	public function getUnites()
	{
		return $this->configuration['unites'];
	}
	public function getActeursPreparationVin()
	{
		return $this->configuration['acteurs_preparation_vin'];
	}
	public function getActeursEmbouteillage()
	{
		return $this->configuration['acteurs_embouteillage'];
	}
	public function getConditionnementsCRD()
	{
		return $this->configuration['conditionnements_crd'];
	}
	public function getPdfPartial()
	{
		return $this->configuration['pdf'];
	}
        
        public function getSoldeSeuil()
	{
		return $this->configuration['solde_seuil'];
	}
}