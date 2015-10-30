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
class DRMLegalSignatureForm extends BaseForm {

    protected $etablissement = null;

    public function __construct($etablissement, $options = array(), $CSRFSecret = null) {
        $this->etablissement = $etablissement;
        parent::__construct($options, $CSRFSecret);
    }

    public function configure() {
        $this->setWidgets(array(
            'terms' => new sfWidgetFormInputCheckbox()
        ));
        $this->setValidators(array('terms' => new sfValidatorPass(array('required' => true))));
        
        $this->widgetSchema->setNameFormat('drm_legal_signature[%s]');
    }
    
    
    public function save() {
        if ($this->getValue('terms')) {
            $societe = $this->etablissement->societe;
            $societe->setLegalSignature();
            $societe->save();
        }
    }

}
