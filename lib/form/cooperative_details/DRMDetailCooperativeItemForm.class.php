<?php

class DRMDetailCooperativeItemForm extends acCouchdbObjectForm {

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }
  
    public function configure() {

        $this->setWidget('identifiant', new sfWidgetFormChoice(array('choices' =>  $this->getCooperatives()), array('class' => 'autocomplete')));
        $this->setWidget('volume', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off', 'class' => 'num num_float')));
        $this->setWidget('date_enlevement', new sfWidgetFormInput());
        
        $this->setValidator('identifiant', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCooperatives()))));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('date_enlevement', new sfValidatorDate(array('required' => false, 
                                                                         'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~',
                                                                         'date_output' => 'Y-m-d')));
        
        $this->validatorSchema->setPostValidator(new DRMDetailCooperativeItemValidator()); 
        $this->widgetSchema->setNameFormat('drm_detail_cooperative_item[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if(!$this->getObject()->date_enlevement) $this->setDefault('date_enlevement', $this->getObject()->getDocument()->getDate());

        $date = new DateTime($this->getDefault('date_enlevement'));
        $this->setDefault('date_enlevement', $date->format('d/m/Y'));
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }
    
    public function getDetail() {
        
        return $this->getObject()->getDetail();
    }

    public function getCooperatives() {
        $etablissements = array('' => '');
        $datas = EtablissementClient::getInstance()->findAll()->rows;
        foreach($datas as $data) {
            $labels = array($data->key[4], $data->key[3], $data->key[1]);
            $etablissements[$data->id] = implode(', ', array_filter($labels));
        }
        return $etablissements;
    }   
}