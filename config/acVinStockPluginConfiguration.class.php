<?php

class acVinStockPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    $this->dispatcher->connect('routing.load_configuration', array('StockRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
