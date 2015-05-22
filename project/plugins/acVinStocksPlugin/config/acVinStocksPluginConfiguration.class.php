<?php

class acVinStocksPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('StocksRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
