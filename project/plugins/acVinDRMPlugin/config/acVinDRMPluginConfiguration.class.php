<?php

class acVinDRMPluginConfiguration extends sfPluginConfiguration {

    public function setup() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            $configCache->registerConfigHandler('config/drm.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'drm_'));
            $configCache->checkConfig('config/drm.yml');
        }
    }

    public function initialize() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            include($configCache->checkConfig('config/drm.yml'));
        }
        $this->dispatcher->connect('routing.load_configuration', array('DRMRouting', 'listenToRoutingLoadConfigurationEvent'));
    }

}
