<?php

class DRMDetailItemValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) { 
    }

    protected function doClean($values) {
      if (isset($values['identifiant']) && $values['identifiant']) {
	if (!$values['volume'])
	  throw new sfValidatorErrorSchema($this, array('volume' => new sfValidatorError($this, 'required'))); 
	return $values;
      }
      return array();
    }
}