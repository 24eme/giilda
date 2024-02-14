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

    public function isIdentifiantSaisi() {
        return isset($this->configuration['identifiant_saisi']) && ($this->configuration['identifiant_saisi']);
    }

    public function getIdentifiantSaisiLibelle() {
        if (!$this->isIdentifiantSaisi()) {
            return false;
        }
        return $this->configuration['identifiant_saisi'];
    }

    public function isElasticDisabled() {
        return isset($this->configuration['elastic_disabled']) && ($this->configuration['elastic_disabled']);
    }

    public function getDroits() {
        if(!isset($this->configuration['droits'])) {

            return array();
        }

        return $this->configuration['droits'];
    }

    public function getDroitLibelle($droit) {
        $droits = $this->getDroits();

        if(isset($droits[$droit])) {

            return $droits[$droit];
        }

        return $droit;
    }

    public function isIdentifantCompteIncremental() {

        return isset($this->configuration['identifiant_compte_incremental']) && ($this->configuration['identifiant_compte_incremental']);
    }

    public function hasNumeroArchive()
    {
        return $this->configuration['has_numero_archive'];
    }
}
