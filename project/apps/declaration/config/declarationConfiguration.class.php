<?php

class declarationConfiguration extends sfApplicationConfiguration
{
    public function configure()
    {
        $configCache = $this->getConfigCache();
        $configCache->registerConfigHandler('config/global.yml', 'sfDefineEnvironmentConfigHandler');
        $configCache->checkConfig('config/global.yml');

        $configCache = $this->getConfigCache();
        $configCache->registerConfigHandler('config/points_aides.yml', 'sfDefineEnvironmentConfigHandler');
        $configCache->checkConfig('config/points_aides.yml');
    }
}
