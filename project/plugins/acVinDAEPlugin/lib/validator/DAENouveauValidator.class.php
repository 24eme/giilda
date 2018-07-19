<?php

class DAENouveauValidator extends sfValidatorBase {
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasError = false;
    	
    	$contenances = VracConfiguration::getInstance()->getContenances();
    	
    	if ($values['conditionnement_key'] == 'bouteille') {
    		if (!$values['contenance_key']) {
    			$errorSchema->addError(new sfValidatorError($this, 'required'), 'contenance');
    			$hasError = true;
    		}
    	}
    	
    	if ($hasError) {
    		throw new sfValidatorErrorSchema($this, $errorSchema);
    	}
    	
        return $values;
    }

}
