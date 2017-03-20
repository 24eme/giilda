<?php

class StatistiquePluginConfiguration extends sfPluginConfiguration
{
    public function setup() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            $configCache->registerConfigHandler('config/statistique.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'statistique_'));
            $configCache->checkConfig('config/statistique.yml');
        }
    }

    public function initialize()
    {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            include($configCache->checkConfig('config/statistique.yml'));
        }

        $this->dispatcher->connect('routing.load_configuration', array('StatistiqueRouting', 'listenToRoutingLoadConfigurationEvent'));
    }
}
