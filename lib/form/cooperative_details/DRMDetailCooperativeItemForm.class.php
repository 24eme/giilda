<?php

class DRMDetailCooperativeItemForm extends acCouchdbObjectForm {

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }
  
    public function configure() {

        $this->setWidget('numero_contrat', new sfWidgetFormChoice(array('choices' =>  $this->getContrats()), array('class' => 'autocomplete')));
        $this->setWidget('volume', new sfWidgetFormInputFloat(array(), array('autocomplete' => 'off')));
        $this->setWidget('date_enlevement', new sfWidgetFormInput());
        
        $this->setValidator('numero_contrat', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getContrats()))));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => true)));
        $this->setValidator('date_enlevement', new sfValidatorDate(array('required' => true, 
                                                                         'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~',
                                                                         'date_output' => 'd/m/Y')));
        
        $this->widgetSchema->setNameFormat('drm_detail_cooperative_item[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
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
                DRMClient::getInstance()->getContratsFromProduit('ETABLISSEMENT-'.$this->getObject()->getDocument()->identifiant, 
                                                                $this->getObject()->getDetail()->getCepage()->getHash())
                );
    }
    
}