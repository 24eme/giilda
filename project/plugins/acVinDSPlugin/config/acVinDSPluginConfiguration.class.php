<?php

class acVinDSPluginConfiguration extends sfPluginConfiguration
{
  public function setup() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            $configCache->registerConfigHandler('config/ds.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'ds_'));
            $configCache->checkConfig('config/ds.yml');
        }
    }

    public function initialize() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            include($configCache->checkConfig('config/ds.yml'));
        }
    }
}
