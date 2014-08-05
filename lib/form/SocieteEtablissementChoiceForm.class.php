<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class SocieteEtablissementChoiceForm extends baseForm {
	
    protected $societe;
    
    public function __construct(Societe $societe, $defaults = array(), $options = array(), $CSRFSecret = null)
    {
        $this->societe = $societe;
        $this->etablissements = $this->societe->getEtablissementsObj();
        parent::__construct($defaults, $options, $CSRFSecret);
    }

    public function configure()
    {
            $this->setWidget('etablissementChoice', new sfWidgetFormChoice(array('choices' => $this->getEtablissements())));
            $this->setValidator('etablissementChoice', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getEtablissements()))));
            $this->widgetSchema->setLabel('etablissementChoice', 'Etablissement*:');
    }
    
    public function getEtablissements() {
        $etablissements = array('0' => 'Choisir un établissement');
        foreach ($this->etablissements as $key => $etablissementObj) {
            $etablissements[$etablissementObj->etablissement->identifiant] = $etablissementObj->etablissement->nom; 
        }
        return $etablissements;
    }
    
}

