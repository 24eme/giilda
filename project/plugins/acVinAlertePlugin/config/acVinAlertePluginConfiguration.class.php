<?php

class acVinAlertePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('AlerteRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
