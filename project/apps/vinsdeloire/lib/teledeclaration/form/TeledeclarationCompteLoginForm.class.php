<?php

class TeledeclarationCompteLoginForm extends BaseForm {

    /**
     * 
     */
    public function configure() {
        $this->setWidgets(array(
                'login'   => new sfWidgetFormInputText(),
        ));

        $this->widgetSchema->setLabels(array(
                'login'  => 'Login : ',
        ));

        $this->setValidators(array(
                'login' => new sfValidatorString(array('required' => true)),
        ));
        
        $this->widgetSchema->setNameFormat('admin[%s]');

        $this->validatorSchema['login']->setMessage('required', 'Champ obligatoire');
        $this->validatorSchema->setPostValidator(new acVinValidatorAdminCompteLogin(array('comptes_type' => $this->getOption('comptes_type', array()))));
    }

    /**
     * 
     * @return _Tiers;
     */
    public function process() {
        if ($this->isValid()) {
            return $this->getValue('compte');
        } else {
            throw new sfException("must be valid");
        }
    }

}

