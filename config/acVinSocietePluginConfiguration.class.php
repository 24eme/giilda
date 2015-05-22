<?php

class acVinSocietePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('SocieteRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
