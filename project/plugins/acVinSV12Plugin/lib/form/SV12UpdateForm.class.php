<?php

class SV12UpdateForm  extends acCouchdbObjectForm {

    private $contrats;
    
    public function __construct(acCouchdbJson $object, $contrats, $options = array(), $CSRFSecret = null) {        
        $this->contrats = $contrats;
        parent::__construct($object, $options, $CSRFSecret);
    }
    
    
    public function configure() {
        
    	foreach ($this->contrats as $value) {
                $num_contrat = preg_replace('/VRAC-/', '', $value->value[VracClient::VRAC_VIEW_NUMCONTRAT]);
                $this->setWidget($num_contrat, new sfWidgetFormInputFloat(array()));
                $this->setValidator($num_contrat, new sfValidatorNumber(array('required' => false)));
    	}  
        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('sv12[%s]');
    }
   
    public function doUpdateObject($values) {
        foreach ($values as $num_contrat => $volume) {
            if($num_contrat!='_revision')
            {
                $this->getObject()->updateVolumeContrat($num_contrat,$volume,$this->getContrat($num_contrat));
            }
        }
    }
    
    private function getContrat($num_contrat) {
        foreach ($this->contrats as $contrat) {
            if(preg_replace('/VRAC-/', '', $contrat->value[VracClient::VRAC_VIEW_NUMCONTRAT])==$num_contrat)
                return $contrat->value;
        }
        return null;
    }

}