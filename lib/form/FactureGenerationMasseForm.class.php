<?php

class FactureGenerationMasseForm extends BaseForm {
    
    private $regions = array('all' => 'Toutes les régions',
                                      'anger' => 'Angers / Tours',
                                      'nantes' => 'Nantes');
    public function configure()
    {
        $this->setWidget('region', new sfWidgetFormChoice(array('choices' => $this->getRegions(),'multiple' => true, 'expanded' => true)));
        $this->setWidget('seuil_facture', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('seuil_avoir', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('date_mouvement', new sfWidgetFormInput());
        
        $this->widgetSchema->setLabels(array(
            'region' => 'Sélectionner des régions à facturer :',
            'seuil_facture' => "Seuil de facturation :",
            'seuil_avoir' => 'Seuil des avoirs :',
            'date_mouvement' => 'Date de facturation :'
        ));
        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }  
        
    
    public function getRegions() {
        return $this->regions;
    }
}
