<?php

class FactureGenerationForm extends BaseForm {
    
    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults['date_facturation'] = date('d/m/Y');
        parent::__construct($defaults, $options, $CSRFSecret);
    }


    public function configure()
    {
        $this->setWidget('date_mouvement', new sfWidgetFormInput());
        $this->setWidget('date_facturation', new sfWidgetFormInput());
        $this->setWidget('message_communication', new sfWidgetFormTextarea());
        
        $this->setValidator('date_mouvement', new sfValidatorString());
        $this->setValidator('date_facturation', new sfValidatorString());
        $this->setValidator('message_communication', new sfValidatorString());
        
        $this->widgetSchema->setLabels(array(
            'message_communication' => 'Cadre de communication :',
            'date_mouvement' => 'Dernière date de prise en compte des mouvements :',
            'date_facturation' => 'Date de facturation :'
        ));
        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }     
}
