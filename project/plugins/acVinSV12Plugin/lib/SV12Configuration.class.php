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
        $this->configuration = sfConfig::get('sv12_configuration_sv12', array());
    }

    public function hasRaisinetmout() {
        return $this->configuration['raisinetmout'];
    }
}
