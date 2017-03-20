<?php

class AppPluginConfiguration extends sfPluginConfiguration
{
    public function setup() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            $configCache->registerConfigHandler('config/points_aides.yml', 'sfDefineEnvironmentConfigHandler');
            $configCache->checkConfig('config/points_aides.yml');
        }
    }

    public function initialize()
    {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            include($configCache->checkConfig('config/points_aides.yml'));
        }

        $this->dispatcher->connect('routing.load_configuration', array('CommonRouting', 'listenToRoutingLoadConfigurationEvent'));
        $this->dispatcher->connect('routing.load_configuration', array('AuthRouting', 'listenToRoutingLoadConfigurationEvent'));
    }

}
