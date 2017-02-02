<?php

class declarationConfiguration extends sfApplicationConfiguration
{
    public function configure()
    {
        $configCache = $this->getConfigCache();
        $configCache->registerConfigHandler('config/global.yml', 'sfDefineEnvironmentConfigHandler');
        include($configCache->checkConfig('config/global.yml'));

        $configCache->registerConfigHandler('config/points_aides.yml', 'sfDefineEnvironmentConfigHandler');
        include($configCache->checkConfig('config/points_aides.yml'));
    }
}
