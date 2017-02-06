<?php

class ivsoConfiguration extends sfApplicationConfiguration
{
    public function configure()
    {
        $configCache = $this->getConfigCache();
        $configCache->registerConfigHandler('config/global.yml', 'sfDefineEnvironmentConfigHandler');
        include($configCache->checkConfig('config/global.yml'));

    }
}
