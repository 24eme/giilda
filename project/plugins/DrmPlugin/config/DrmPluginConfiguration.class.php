<?php

class DrmPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('DrmRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
