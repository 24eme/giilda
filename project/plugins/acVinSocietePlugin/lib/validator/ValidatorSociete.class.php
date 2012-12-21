<?php

class ValidatorSociete extends acValidatorCouchdbDocument
{
    public function __construct($options = array(), $messages = array())
    {
        parent::__construct($options, $messages);
    }

    protected function configure($options = array(), $messages = array())
    {
        parent::configure($options, $messages);
        $this->setOption('type', 'Societe');
        $this->setOption('prefix', '');
        $this->addOption('type_societe', array());
    }    
}