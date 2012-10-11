<?php

class FactureGenerationMasseForm extends FactureGenerationForm {

    private $regions = array('angers' => 'Angers',
        'nantes' => 'Nantes',
        'tours' => 'Tours');

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        
        $this->setWidget('regions', new sfWidgetFormChoice(array('choices' => $this->getRegions(), 'multiple' => true, 'expanded' => true, 'default' => array_keys($this->getRegions()))));
        $this->setWidget('seuil', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('date_mouvements', new sfWidgetFormInput(array('default' => date('d/m/Y'))));
        $this->setWidget('date_facturation', new sfWidgetFormInput(array('default' => date('d/m/Y'))));

        $this->setValidator('regions', new sfValidatorChoice(array('choices' => array_keys($this->getRegions()), 'multiple' => true)));
	$this->setValidator('seuil', new sfValidatorNumber());
        $this->setValidator('date_mouvements', new sfValidatorString());
        $this->setValidator('date_facturation', new sfValidatorString());
        
        $this->widgetSchema->setLabels(array(
            'regions' => 'Sélectionner des régions à facturer :',
            'seuil_facture' => "Seuil de facturation :",
            'seuil_avoir' => 'Seuil des avoirs :',
            'date_mouvements' => 'Dernière date de prise en compte des mouvements :',
            'date_facturation' => 'Date de facturation :'
        ));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }

    public function getRegions() {
        return $this->regions;
    }

}
