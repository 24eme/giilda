<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class AlertesConsultationForm
 * @author mathurin
 */
class AlertesConsultationForm extends sfForm {     
    
    private $anneeCampagneStart = 2007;
    
    public function configure()
    {   
        $this->setWidget('identifiant', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire', 'familles' => array(EtablissementFamilles::FAMILLE_PRODUCTEUR,  EtablissementFamilles::FAMILLE_NEGOCIANT))));  
        $this->setWidget('region_alerte',new sfWidgetFormChoice(array('choices' => $this->getRegions(),'expanded' => false)));  
        $this->setWidget('type_alerte', new sfWidgetFormChoice(array('choices' => $this->getTypes(),'expanded' => false)));      
        $this->setWidget('statut_alerte',new sfWidgetFormChoice(array('choices' => $this->getStatuts(),'expanded' => false)));    
        $this->setWidget('campagne_alerte',new sfWidgetFormChoice(array('choices' => $this->getCampagnes(),'expanded' => false))); 
        
        $this->widgetSchema->setLabels(array(
            'identifiant' => 'Rechercher un opérateur :',
            'region_alerte' => 'Region viticole :',
            'type_alerte' => "Type d'alerte :",
            'statut_alerte' => "Statut d'alerte :",            
            'campagne_alerte' => 'Campagne :'));
        
        $this->setValidator('identifiant',new ValidatorEtablissement(array('required' => true)));
        $this->setValidator('region_alerte',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getRegions()))));
        $this->setValidator('type_alerte',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypes()))));
        $this->setValidator('statut_alerte',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
        $this->setValidator('campagne_alerte',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCampagnes()))));        
        $this->widgetSchema->setNameFormat('alerte_consultation[%s]');        
    }
    
    private function getTypes()
    {
        return AlerteClient::$alertes_libelles;
    }
    
    private function getStatuts()
    {
        return AlerteClient::getStatutsWithLibelles();
    }
    
    private function getRegions() {
        return EtablissementClient::getRegions();
    }
    
    private function getCampagnes() {
        $annee = date('Y');
        $campagnes = array();
        for ($currentA = $annee; $currentA > $this->anneeCampagneStart; $currentA--) {
            $elt = $currentA.'-'.($currentA+1);
            $campagnes[$elt] = $elt;
        }
        return $campagnes;
    }
}

