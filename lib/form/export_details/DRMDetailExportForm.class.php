<?php

class DRMDetailExportForm extends acCouchdbForm {
    
    protected $drm_sorties_export_details = null;
    
    public function __construct(acCouchdbJson $drm_sorties_export_detail, $defaults = array(), $options = array(), $CSRFSecret = null) {
        
        $this->drm_sorties_export_details = $drm_sorties_export_detail;        
        parent::__construct($this->drm_sorties_export_details->getDocument(), $options, $CSRFSecret);
    }
  
    public function configure() {

        if(!count($this->drm_sorties_export_details))
        {
            $this->drm_sorties_export_details->add();
        }
        
        foreach ($this->drm_sorties_export_details as $key => $export) {
            $form = $this->embedForm($key, new DRMDetailExportItemForm($export));
        }
        
        $this->widgetSchema->setNameFormat('drm_detail_export[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        foreach ($this->embeddedForms as $key => $form)
        {
            if(!array_key_exists($key, $taintedValues)){
                $this->unembedForm($key);
            }
        }
        
        foreach($taintedValues as $key => $values) {
            if(!is_array($values) || array_key_exists($key, $this->embeddedForms)) {                
                continue;
            }
                
            $this->embedForm($key, new DRMDetailExportItemForm($this->drm_sorties_export_details->add()));
        }        
        parent::bind($taintedValues, $taintedFiles);
    }
    
    public function update() {
        foreach($this->getEmbeddedForms() as $key => $form) {
            $form->updateObject($this->values[$key]);
        }
    }
    
    public function getExportDetails()
    {
    return $this->drm_sorties_export_details;
    }
    
    public function getFormTemplate() {
        $form = new DRMDetailExportTemplateForm($this->drm_sorties_export_details);
        return $form->getFormTemplate();
    }
    
    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->drm_sorties_export_details->remove($key);
    }
    
}