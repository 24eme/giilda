<?php

class FactureGenerationForm extends BaseForm {
    
    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults['date_facturation'] = date('d/m/Y');
        $defaults['type_document'] = FactureClient::FACTURE_LIGNE_ORIGINE_TYPE_DRM;
        parent::__construct($defaults, $options, $CSRFSecret);
    }


    public function configure()
    {
        
        $this->setWidget('modele', new sfWidgetFormChoice(array('choices' => $this->getChoices())));
        $this->setWidget('date_mouvement', new bsWidgetFormInputDate());
        $this->setWidget('date_facturation', new bsWidgetFormInputDate());
        $this->setWidget('message_communication', new sfWidgetFormTextarea());
        
        $this->setValidator('modele', new sfValidatorChoice(array('choices' => array_keys($this->getChoices()), 'required' => true)));
        $this->setValidator('date_mouvement', new sfValidatorString(array('required' => false)));
        $this->setValidator('date_facturation', new sfValidatorString());
        $this->setValidator('message_communication', new sfValidatorString(array('required' => false)));
        
        $this->widgetSchema->setLabels(array(
            'modele' => "Type de facturation :",
            'message_communication' => 'Cadre de communication :',
            'date_mouvement' => 'Dernière date de prise en compte des mouvements :',
            'date_facturation' => 'Date de facturation :'
        ));
        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }     

   public function getChoices() {
        $choices = array_merge(array("" => ""),  FactureClient::$type_facture_mouvement);
    
        return $choices;
    }
}
