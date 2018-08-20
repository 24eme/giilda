<?php

class DRMDeclaratifValidator extends sfValidatorSchema
{

    public function configure($options = array(), $messages = array())
    {
        $this->addOption('organisme_field', 'organisme');
    }

    protected function doClean($values)
    {
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'required');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('organisme_field') => new sfValidatorError($this, 'required')));
    }

}
