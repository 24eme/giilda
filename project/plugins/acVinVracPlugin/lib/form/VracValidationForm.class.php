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
        $this->setValidator('date_signature', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => true)));

        $this->widgetSchema->setNameFormat('vrac[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if($this->getValidator('date_signature') instanceof sfValidatorDate) {
            $this->setDefault('date_signature', $this->getObject()->getDateSignature('d/m/Y'));
        }
    }
    
    public function doUpdateObject($values) 
    {
        parent::doUpdateObject($values);
    }
    
}

