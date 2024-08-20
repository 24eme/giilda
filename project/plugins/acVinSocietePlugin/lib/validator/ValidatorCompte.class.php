<?php

class ValidatorCompte extends acValidatorCouchdbDocument
{
    public function __construct($options = array(), $messages = array())
    {
        parent::__construct($options, $messages);
    }

    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
        $this->setOption('type', 'Compte');
        $this->setOption('prefix', '');
        $this->addOption('familles', array());
    }
}
