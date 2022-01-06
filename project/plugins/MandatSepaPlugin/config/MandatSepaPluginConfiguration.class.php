<?php

class MandatSepaPluginConfiguration extends sfPluginConfiguration
{
  public function setup() {
      if ($this->configuration instanceof sfApplicationConfiguration) {
          $configCache = $this->configuration->getConfigCache();
          $configCache->registerConfigHandler('config/mandatsepa.yml', 'sfDefineEnvironmentConfigHandler');
          $configCache->checkConfig('config/mandatsepa.yml');
      }
  }

  public function initialize() {
      if ($this->configuration instanceof sfApplicationConfiguration) {
          $configCache = $this->configuration->getConfigCache();
          include($configCache->checkConfig('config/mandatsepa.yml'));
      }
  }
}
