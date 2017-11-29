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


    public function configure()
    {
        $this->setWidget('identifiant', new WidgetEtablissement(array('interpro_id' => 'INTERPRO-inter-loire', 'familles' => array(EtablissementFamilles::FAMILLE_PRODUCTEUR,  EtablissementFamilles::FAMILLE_NEGOCIANT))));
        $this->setWidget('region',new sfWidgetFormChoice(array('choices' => $this->getRegions(),'expanded' => false)));
        $this->setWidget('type_alerte', new sfWidgetFormChoice(array('choices' => $this->getTypes(),'expanded' => false)));
        $this->setWidget('statut_courant',new sfWidgetFormChoice(array('choices' => $this->getStatuts(),'expanded' => false)));
        $this->setWidget('campagne',new sfWidgetFormChoice(array('choices' => $this->getCampagnes(),'expanded' => false)));

        $this->widgetSchema->setLabels(array(
            'identifiant' => 'Rechercher un opérateur :',
            'region' => 'Region viticole :',
            'type_alerte' => "Type d'alerte :",
            'statut_courant' => "Statut d'alerte :",
            'campagne' => 'Campagne :'));

        $this->setValidator('identifiant',new ValidatorEtablissement(array('required' => false)));
        $this->setValidator('region',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getRegions()))));
        $this->setValidator('type_alerte',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getTypes()))));
        $this->setValidator('statut_courant',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getStatuts()))));
        $this->setValidator('campagne',new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getCampagnes()))));
        $this->widgetSchema->setNameFormat('consultation[%s]');
    }

    public function hasFilters()
    {
    	$values = $this->values;
    	$hasFilter = false;
    	foreach ($values as $value) {
    		if ($value) {
    			$hasFilter = true;
    			break;
    		}
    	}
    	return $hasFilter;
    }

    private function getTypes()
    {
    	$choices = array_merge(array('' => ''), AlerteClient::$alertes_libelles);
        return $choices;
    }

    private function getStatuts()
    {
    	$choices = array_merge(array('' => ''), AlerteClient::getStatutsWithLibelles());
        return $choices;
    }

    private function getRegions() {
    	$choices = array_merge(array('' => ''), EtablissementClient::getRegionsWithoutHorsInterLoire(true));
        return $choices;
    }

    private function getCampagnes() {
        $annee = date('Y');
        $campagnes = array('' => '');
        $anneeCampagneStart = substr(AlerteClient::FIRSTCAMPAGNEIMPORT, 0,4);
        for ($currentA = $annee; $currentA >= $anneeCampagneStart; $currentA--) {
            $elt = $currentA.'-'.($currentA+1);
            $campagnes[$elt] = $elt;
        }
        return $campagnes;
    }
}
