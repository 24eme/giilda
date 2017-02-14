<?php

class acVinValidatorAdminCompteLogin extends sfValidatorBase {
    
    public function configure($options = array(), $messages = array()) {
        $this->setMessage('invalid', "Ce login n'existe pas");
        $this->addOption('comptes_type', array());
    }

    protected function doClean($values) {
        if (!$values['login']) {
            return array_merge($values);
        }
        
        $compte = CompteClient::getInstance()->findByLogin($values['login']);
        if (!$compte) {            
            throw new sfValidatorErrorSchema($this, array($this->getOption('login') => new sfValidatorError($this, 'invalid')));
        }        
        if (!in_array($compte->getType(), $this->getOption('comptes_type'))) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('login') => new sfValidatorError($this, 'invalid')));
        }
        
        return array_merge($values, array('compte' => $compte));
    }

}
