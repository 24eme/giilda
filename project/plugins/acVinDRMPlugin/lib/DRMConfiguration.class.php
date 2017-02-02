<?php

class DRMConfiguration {

    private static $_instance = null;
    protected $configuration;

    const ALL_KEY = "_ALL";

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new DRMConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->configuration = sfConfig::get('drm_configuration_drm', array());
    }

    public function getExportDetail() {
        return $this->configuration['details']['export_detail'];
    }

    public function getContenances() {
        return $this->configuration['contenances'];
    }

    public function isVracCreation() {
        return boolval($this->configuration['details']['vrac_detail']['creation']);
    }
    public function isDRMNegoce() {
        return boolval($this->configuration['negoce']);
    }
}
