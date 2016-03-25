<?php

class acVinFacturePluginConfiguration extends sfPluginConfiguration
{
  public function setup() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            $configCache->registerConfigHandler('config/facture.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'facture_'));
            $configCache->checkConfig('config/facture.yml');
        }
    }

    public function initialize() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            include($configCache->checkConfig('config/facture.yml'));
        }
        $this->dispatcher->connect('routing.load_configuration', array('FactureRouting', 'listenToRoutingLoadConfigurationEvent'));
    }
}
