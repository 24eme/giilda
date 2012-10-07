<?php

class acVinRevendicationPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('RevendicationRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
