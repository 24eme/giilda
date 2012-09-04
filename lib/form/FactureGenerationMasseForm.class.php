<?php

class FactureGenerationMasseForm extends BaseForm {
    
    private $regions = array('angers' => 'Angers',
                             'nantes' => 'Nantes',
                             'tours' => 'Tours');
    public function configure()
    {
        $this->setWidget('region', new sfWidgetFormChoice(array('choices' => $this->getRegions(),'multiple' => true, 'expanded' => true)));
        $this->setWidget('seuil', new sfWidgetFormInput(array(), array('autocomplete' => 'off')));
        $this->setWidget('date_mouvement', new sfWidgetFormInput());
        $this->setWidget('date_facturation', new sfWidgetFormInput());
        
        $this->widgetSchema->setLabels(array(
            'region' => 'Sélectionner des régions à facturer :',
            'seuil_facture' => "Seuil de facturation :",
            'seuil_avoir' => 'Seuil des avoirs :',
            'date_mouvement' => 'Dernière date de prise en compte des mouvements :',
            'date_facturation' => 'Date de facturation :'
        ));
        $this->widgetSchema->setNameFormat('facture_generation[%s]');
    }  
        
    
    public function getRegions() {
        return $this->regions;
    }
}
