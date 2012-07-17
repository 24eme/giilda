<?php

class DRMDetailCooperativeTemplateForm extends DRMDetailCooperativeForm {
    
    public function __construct(acCouchdbJson $drm_sorties_cooperative_detail, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($drm_sorties_cooperative_detail, $defaults, $options, $CSRFSecret);
        unset($this['_revision']);
    }


    public function configure() {

        $this->embedForm('var---nbItem---', new DRMDetailCooperativeItemForm($this->drm_sorties_cooperative_details->add()));
        $this->widgetSchema->setNameFormat('drm_detail_cooperative[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function getFormTemplate() {
        
        return $this['var---nbItem---'];
    }
    
}