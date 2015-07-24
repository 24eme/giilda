<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class VracValidationForm
 * @author mathurin
 */
class VracValidationForm extends acCouchdbObjectForm {
	
    protected $isTeledeclarationMode;

    public function __construct(Vrac $vrac, $isTeledeclarationMode = false, $options = array(), $CSRFSecret = null) {
        $this->isTeledeclarationMode = $isTeledeclarationMode;
        parent::__construct($vrac, $options, $CSRFSecret);
    }
   
    
     
    public function configure()
    {
        $this->setWidget('date_signature', new bsWidgetFormInput());
        $dateRegexpOptions = array('required' => true,
        		'pattern' => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/",
        		'min_length' => 10,
        		'max_length' => 10);
        $dateRegexpErrors = array('required' => 'Cette obligatoire',
        		'invalid' => 'Date invalide (le format doit être jj/mm/aaaa)',
        		'min_length' => 'Date invalide (le format doit être jj/mm/aaaa)',
        		'max_length' => 'Date invalide (le format doit être jj/mm/aaaa)');
        $this->setValidator('date_signature', new sfValidatorRegex($dateRegexpOptions, $dateRegexpErrors));
        $this->widgetSchema->setNameFormat('vrac[%s]');
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
    }
    
}

