<?php

class DRMDetailVracItemForm extends acCouchdbObjectForm {

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }
  
    public function configure() {

        $this->setWidget('identifiant', new sfWidgetFormChoice(array('choices' =>  $this->getContrats()), array('class' => 'autocomplete')));
        $this->setWidget('volume', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off', 'class' => 'num num_float')));
        $this->setWidget('date_enlevement', new sfWidgetFormInput());
        
        $this->setValidator('identifiant', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContrats()))));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => true)));
        $this->setValidator('date_enlevement', new sfValidatorDate(array('required' => true, 
                                                                         'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~',
                                                                         'date_output' => 'd/m/Y')));
        
        $this->widgetSchema->setNameFormat('drm_detail_vrac_item[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if(!$this->getObject()->date_enlevement) $this->setDefault('date_enlevement', DRMClient::getInstance()->getDetailsDefaultDate());
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }
    
    public function getDetail() {
        
        return $this->getObject()->getDetail();
    }

    public function getContrats() {

        return array_merge(
                array("" => ""),
                DRMClient::getInstance()->getContratsFromProduit($this->getObject()->getDocument()->identifiant, 
                                                                $this->getObject()->getDetail()->getCepage()->getHash())
                );
    }
    
    public function getDefaultDate() {
        return date('d/m/Y');
    }
    
    
}
