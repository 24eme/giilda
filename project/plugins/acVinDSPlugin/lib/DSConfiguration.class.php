<?php

class DSConfiguration {

    private static $_instance = null;
    protected $configuration;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new DSConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        if(!sfConfig::has('ds_configuration_ds')) {
			       throw new sfException("La configuration pour les DS n'a pas été définie pour cette application");
		    }
        $this->configuration = sfConfig::get('ds_configuration_ds', array());
    }

    public function getAll() {
        return $this->configuration;
    }

    public function getName() {
        return $this->configuration['name'];
    }

    public function getDateStockDeclaration() {
        return $this->configuration['date_stock_declaration'];
    }

    public function getTitle() {
        return $this->configuration['title'];
    }

    public function getProductHashRegexFilter() {
        return $this->configuration['product_hash_regex_filter'];
    }

    public function getProductDetailInterpro() {
        return $this->configuration['product_detail_interpro'];
    }

    public function hideHistorique() {
        return $this->configuration['hide_historique'];
    }

    public function execptionProduit() {
        return $this->configuration['exception_produit'];
    }

}
