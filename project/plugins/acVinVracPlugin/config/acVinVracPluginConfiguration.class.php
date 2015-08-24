<?php

class acVinVracPluginConfiguration extends sfPluginConfiguration
{
	public function setup()
	{
		if ($this->configuration instanceof sfApplicationConfiguration) {
			$configCache = $this->configuration->getConfigCache();
			$configCache->registerConfigHandler('config/vrac.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'vrac_'));
			$configCache->checkConfig('config/vrac.yml');
		}
	}
  	public function initialize()
  	{
  		if ($this->configuration instanceof sfApplicationConfiguration) {
  			$configCache = $this->configuration->getConfigCache();
  			include($configCache->checkConfig('config/vrac.yml'));
  		}
      	$this->dispatcher->connect('routing.load_configuration', array('VracRouting', 'listenToRoutingLoadConfigurationEvent'));
  	}
}
