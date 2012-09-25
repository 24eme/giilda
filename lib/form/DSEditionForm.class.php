<?php

class DSEditionForm extends acCouchdbObjectForm {

    
    protected $declarations = null;
    
    public function __construct(acCouchdbJson $declarations, $defaults = array(), $options = array(), $CSRFSecret = null) {
        
        $this->declarations = $declarations;        
        parent::__construct($this->declarations->getDocument(), $options, $CSRFSecret);
    }
    
    public function configure()
    {
        if(!count($this->declarations))
        {
            $this->declarations->add();
        }
        
        foreach ($this->declarations as $key => $declaration) {
            $form = $this->embedForm($key, new DSEditionItemForm($declaration));
        }
        
        $this->widgetSchema->setNameFormat('declaration[%s]');        
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
                
            $this->embedForm($key, new DSEditionItemForm($this->declarations->add()));
        }        
        parent::bind($taintedValues, $taintedFiles);
    }

}