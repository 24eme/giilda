<?php

class VracPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('VracRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
