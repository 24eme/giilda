<?php

class DRMDetailVracForm extends acCouchdbForm {
    
    protected $drm_sorties_vrac_details = null;
    
    public function __construct(acCouchdbJson $drm_sorties_vrac_detail, $defaults = array(), $options = array(), $CSRFSecret = null) {
        
        $this->drm_sorties_vrac_details = $drm_sorties_vrac_detail;        
        parent::__construct($this->drm_sorties_vrac_details->getDocument(), $options, $CSRFSecret);
    }
  
    public function configure() {

        if(!count($this->drm_sorties_vrac_details))
        {
            $this->drm_sorties_vrac_details->add();
        }
        
        foreach ($this->drm_sorties_vrac_details as $key => $vrac) {
            $form = $this->embedForm($key, new DRMDetailVracItemForm($vrac));
        }
        
        $this->widgetSchema->setNameFormat('drm_detail_vrac[%s]');
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
                
            $this->embedForm($key, new DRMDetailVracItemForm($this->drm_sorties_vrac_details->add()));
        }        
        parent::bind($taintedValues, $taintedFiles);
    }
    
    public function update() {
        //$this->drm_sorties_vrac_details->clear();
        foreach($this->getEmbeddedForms() as $key => $form) {
            $form->updateObject($this->values[$key]);
        }
    }
    
    public function getVracDetails()
    {
    return $this->drm_sorties_vrac_details;
    }
    
    public function getFormTemplate() {
        $form = new DRMDetailVracTemplateForm($this->drm_sorties_vrac_details);
        return $form->getFormTemplate();
    }
    
    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->drm_sorties_vrac_details->remove($key);
    }
    
}