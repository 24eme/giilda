<?php

class acVinSV12PluginConfiguration extends sfPluginConfiguration
{

    public function setup() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            $configCache->registerConfigHandler('config/sv12.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'sv12_'));
            $configCache->checkConfig('config/sv12.yml');
        }
    }

    public function initialize() {
        if ($this->configuration instanceof sfApplicationConfiguration) {
            $configCache = $this->configuration->getConfigCache();
            include($configCache->checkConfig('config/sv12.yml'));
        }
        $this->dispatcher->connect('routing.load_configuration', array('SV12Routing', 'listenToRoutingLoadConfigurationEvent'));
    }
}
