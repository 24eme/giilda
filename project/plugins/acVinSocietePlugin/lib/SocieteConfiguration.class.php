<?php

class SocieteConfiguration {

    private static $_instance = null;
    protected $configuration;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new SocieteConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        if(!sfConfig::has('societe_configuration_societe')) {
			throw new sfException("La configuration pour les sociétés n'a pas été défini pour cette application");
		}

        $this->configuration = sfConfig::get('societe_configuration_societe', array());
    }

    public function getExtras() {

        return isset($this->configuration['extras']) ? $this->configuration['extras'] : array();
    }

}
