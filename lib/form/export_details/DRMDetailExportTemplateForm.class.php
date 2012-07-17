<?php

class DRMDetailExportTemplateForm extends DRMDetailExportForm {
    
    public function __construct(acCouchdbJson $drm_sorties_export_detail, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($drm_sorties_export_detail, $defaults, $options, $CSRFSecret);
        unset($this['_revision']);
    }


    public function configure() {

        $this->embedForm('var---nbItem---', new DRMDetailExportItemForm($this->drm_sorties_export_details->add()));
        $this->widgetSchema->setNameFormat('drm_detail_export[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function getFormTemplate() {
        
        return $this['var---nbItem---'];
    }
    
}