<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMLegalSignatureForm
 *
 * @author tangui
 */
class DRevLegalSignatureForm extends BaseForm {

    protected $societe = null;

    public function __construct($societe, $options = array(), $CSRFSecret = null) {
        $this->societe = $societe;
        parent::__construct($options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'terms' => new sfWidgetFormInputCheckbox()
        ));
        $this->setValidators(array('terms' => new sfValidatorPass(array('required' => true))));

        $this->widgetSchema->setNameFormat('drev_legal_signature[%s]');
    }


    public function save() {
        if ($this->getValue('terms')) {
            $this->societe->setLegalSignature('drev');
            $this->societe->save();
        }
    }

}
