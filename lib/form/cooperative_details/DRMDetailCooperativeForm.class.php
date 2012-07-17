<?php

class DRMDetailCooperativeForm extends acCouchdbForm {
    
    protected $drm_sorties_cooperative_details = null;
    
    public function __construct(acCouchdbJson $drm_sorties_cooperative_detail, $defaults = array(), $options = array(), $CSRFSecret = null) {
        
        $this->drm_sorties_cooperative_details = $drm_sorties_cooperative_detail;        
        parent::__construct($this->drm_sorties_cooperative_details->getDocument(), $options, $CSRFSecret);
    }
  
    public function configure() {

        if(!count($this->drm_sorties_cooperative_details))
        {
            $this->drm_sorties_cooperative_details->add();
        }
        
        foreach ($this->drm_sorties_cooperative_details as $key => $cooperative) {
            $form = $this->embedForm($key, new DRMDetailcooperativeItemForm($cooperative));
        }
        
        $this->widgetSchema->setNameFormat('drm_detail_cooperative[%s]');
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
                
            $this->embedForm($key, new DRMDetailCooperativeItemForm($this->drm_sorties_cooperative_details->add()));
        }        
        parent::bind($taintedValues, $taintedFiles);
    }
    
    public function update() {
        //$this->drm_sorties_cooperative_details->clear();
        foreach($this->getEmbeddedForms() as $key => $form) {
            $form->updateObject($this->values[$key]);
        }
    }
    
    public function getCooperativeDetails()
    {
    return $this->drm_sorties_cooperative_details;
    }
    
    public function getFormTemplate() {
        $form = new DRMDetailcooperativeTemplateForm($this->drm_sorties_cooperative_details);
        return $form->getFormTemplate();
    }
    
    protected function unembedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->drm_sorties_cooperative_details->remove($key);
    }
    
}