<?php

class StatistiqueConfiguration {

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
        $this->configuration = sfConfig::get('statistique_configuration_statistique', array());
    }

    public function isActif() {
        
        return $this->configuration['actif'];
    }
}
