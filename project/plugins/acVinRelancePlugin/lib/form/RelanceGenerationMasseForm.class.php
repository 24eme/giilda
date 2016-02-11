<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class RelanceGenerationMasseForm
 * @author mathurin
 */
class RelanceGenerationMasseForm extends BaseForm {

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        $defaults['date_relance'] = date('d/m/Y');
        $defaults['types_relance'] = RelanceClient::TYPE_RELANCE_DRM_MANQUANTE;
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure() {
        
       $this->setWidget('types_relance', new sfWidgetFormChoice(array('choices' => $this->getTypesRelance(), 'multiple' => false, 'expanded' => true, 'default' => array_keys($this->getRegions()))));
        $this->setWidget('date_relance', new sfWidgetFormInput(array('default' => date('d/m/Y'))));
        
       $this->setValidator('types_relance', new sfValidatorChoice(array('choices' => array_keys($this->getTypesRelance()), 'multiple' => false, 'required' => false)));
       $this->setValidator('date_relance', new sfValidatorString());
	
        
        $this->widgetSchema->setLabels(array(
            'types_relance' => 'Sélectionner les alertes à relancer :',
            'date_relance' => 'Date de relance :'
        ));
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('relancer_generation[%s]');
    }

    public function getRegions() {
        return EtablissementClient::getRegionsWithoutHorsInterpro();
    }

    public function getTypesRelance() {
        return RelanceClient::$relances_types_libelles;
    }
    
}
