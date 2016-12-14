<?php

class FactureConfiguration {

    private static $_instance = null;
    protected $configuration;

    const ALL_KEY = "_ALL";

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new FactureConfiguration();
        }
        return self::$_instance;
    }

      public function __construct() {
        $this->configuration = sfConfig::get('facture_configuration_facture', array());
    }

   public function getPrefixId($facture) {
        if ($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DIVERS)) {

            return $this->configuration['type_libre']['identifiant_prefix'];
        }
        if ($facture->hasArgument(FactureClient::TYPE_FACTURE_MOUVEMENT_DRM)) {

            return $this->configuration['type_cvo']['identifiant_prefix'];
        }

    }

    public function getPrefixSage() {

        return $this->configuration['prefix_sage'];
    }

    public function getPrefixCodeComptable() {

        return isset($this->configuration['prefix_code_comptable']) ? $this->configuration['prefix_code_comptable'] : null;
    }

    public function getTVACompte() {

        return $this->configuration['tva_compte'];
    }

    public function getStockageCodeProduit() {

        return $this->configuration['stockage_code_produit'];
    }

}
