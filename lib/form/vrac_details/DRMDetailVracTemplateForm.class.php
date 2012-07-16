<?php

class DRMDetailVracTemplateForm extends DRMDetailVracForm {
    
    public function __construct(acCouchdbJson $drm_sorties_vrac_detail, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($drm_sorties_vrac_detail, $defaults, $options, $CSRFSecret);
        unset($this['_revision']);
    }


    public function configure() {

        $this->embedForm('var---nbItem---', new DRMDetailVracItemForm($this->drm_sorties_vrac_details->add()));
        $this->widgetSchema->setNameFormat('drm_detail_vrac[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function getFormTemplate() {
        
        return $this['var---nbItem---'];
    }
    
}