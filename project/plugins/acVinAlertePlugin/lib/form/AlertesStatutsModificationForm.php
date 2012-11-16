<?php

class AlertesStatutsModificationForm extends sfForm {     
    
    private $alertesList = null;
    
    public function __construct($alertesList, $defaults = array(), $options = array(), $CSRFSecret = null) {
        $this->alertesList = $alertesList;
        parent::__construct($defaults, $options, $CSRFSecret);
    }
    
    public function configure()
    {   
       $this->setWidget('statut_all_alertes',new sfWidgetFormChoice(array('choices' => $this->getStatuts(),'expanded' => false)));   
       
       foreach ($this->alertesList as $alerte) {
            $this->setWidget($alerte->id, new sfWidgetFormInputCheckbox());   
            $this->setValidator($alerte->id,new sfValidatorChoice(array('required' => false, 'choices' => array('0' => 0, '1' => 1)))); 
       }
       $this->widgetSchema->setLabel('statut_all_alertes','Choisir un statut pour toutes les alertes : ');
       $this->setValidator('statut_all_alertes',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));          
    }
    
    private function getStatuts()
    {
        return AlerteClient::getStatutsWithLibelles();
    }

    
}
