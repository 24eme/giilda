<?php

class SV12Configuration {

    private static $_instance = null;
    protected $configuration;

    const ALL_KEY = "_ALL";

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new SV12Configuration();
        }
        return self::$_instance;
    }

    public function __construct() {
        if(!sfConfig::has('sv12_configuration_sv12')) {
			throw new sfException("La configuration pour les sv12 n'a pas été défini pour cette application");
		}

        $this->configuration = sfConfig::get('sv12_configuration_sv12', array());
    }

    public function getAll() {

        return $this->configuration;
    }

    public function hasRaisinetmout() {

        return $this->configuration['raisinetmout'];
    }

    public function isActif() {

        return $this->configuration['actif'];
    }
}
