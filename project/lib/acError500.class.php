<?php

class acError500 {
  public static function handleException(sfEvent $event) {
    $moduleName = sfConfig::get('sf_error_500_module', 'error');
    $actionName = sfConfig::get('sf_error_500_action', 'error500');
    sfContext::getInstance()->getRequest()->addRequestParameters(array('exception' => $event->getSubject()));
    sfContext::getInstance()->getController()->forward($moduleName, $actionName);
    $event->setReturnValue(false);
    if (!sfConfig::get('sf_error_500_includeexception')) {
      $event->setProcessed(true);
    }
    return false;
  }
}
