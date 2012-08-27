<?php

class SV12UpdateForm  extends acCouchdbForm {
    

    public function __construct(acCouchdbDocument $doc, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults = array();
        foreach ($doc->getContrats() as $value) {
                $defaults[$value->contrat_numero] = $value->volume;
    	}  
        
        parent::__construct($doc,$defaults, $options, $CSRFSecret);
   }
    
    
    public function configure() {  
    	foreach ($this->getDocument()->getContrats() as $value) {
                $this->setWidget($value->contrat_numero, new sfWidgetFormInputFloat(array()));
                $this->setValidator($value->contrat_numero, new sfValidatorNumber(array('required' => false)));
    	}  
        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('sv12[%s]');
    }
    
    public function doUpdateObject() {
        $values = $this->values;
        foreach ($values as $num_contrat => $volume) {
            if($this->getDocument()->contrats->exist($num_contrat))
            {
                $this->getDocument()->contrats[$num_contrat]->volume = $volume;
            }
        }
    }
}