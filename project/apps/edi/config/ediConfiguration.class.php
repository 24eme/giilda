<?php

class ediConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
      $this->dispatcher->connect('application.throw_exception', array('acError500', 'handleException'));
  }
}
