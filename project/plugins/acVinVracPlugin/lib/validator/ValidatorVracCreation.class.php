<?php

class ValidatorVracCreation extends sfValidatorBase 
{
    protected function doClean($values) 
    {
        $errorSchema = new sfValidatorErrorSchema($this);
        if (!$values['annee'] && $values['bordereau']) {
        	$errorSchema->addError(new sfValidatorError($this, 'required'), 'annee');
        }
        if ($values['annee'] && !$values['bordereau']) {
        	$errorSchema->addError(new sfValidatorError($this, 'required'), 'bordereau');
        }
        if (count($errorSchema)) {
            throw new sfValidatorErrorSchema($this, $errorSchema);
        }
        return $values;
    }
}