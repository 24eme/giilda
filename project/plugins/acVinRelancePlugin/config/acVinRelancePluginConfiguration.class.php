<?php

class acVinRelancePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('RelanceRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
