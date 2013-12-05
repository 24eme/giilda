<?php

class FactureGenerationMasseForm extends FactureGenerationForm {

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults['date_facturation'] = date('d/m/Y');
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        
        $this->setWidget('regions', new sfWidgetFormChoice(array('choices' => $this->getRegions(), 'multiple' => true, 'expanded' => true, 'default' => array_keys($this->getRegions()))));
        $this->setWidget('seuil', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('date_mouvement', new sfWidgetFormInput(array('default' => date('d/m/Y'))));
        $this->setWidget('date_facturation', new sfWidgetFormInput(array('default' => date('d/m/Y'))));
        $this->setWidget('message_communication', new sfWidgetFormTextarea());
  
        $this->setValidator('regions', new sfValidatorChoice(array('choices' => array_keys($this->getRegions()), 'multiple' => true, 'required' => false)));
	$this->setValidator('seuil', new sfValidatorNumber(array('required' => false)));
        $this->setValidator('date_mouvement', new sfValidatorString());
        $this->setValidator('date_facturation', new sfValidatorString());
        $this->setValidator('message_communication', new sfValidatorString());
        
        $this->widgetSchema->setLabels(array(
            'regions' => 'Sélectionner des régions à facturer :',
            'seuil_facture' => "Seuil de facturation :",
            'seuil_avoir' => 'Seuil des avoirs :',
            'date_mouvements' => 'Dernière date de prise en compte des mouvements :',
            'date_facturation' => 'Date de facturation :',
            'message_communication' => 'Cadre de communication :'
        ));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }

    public function getRegions() {
        return EtablissementClient::getRegionsWithoutHorsInterLoire();
    }

}
