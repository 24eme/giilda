<?php

class StatistiquePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('StatistiqueRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
