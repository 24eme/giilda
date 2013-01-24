<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteModificationEtbForm
 * @author mathurin
 */
class CompteModificationEtbForm extends acCouchdbObjectForm {
    
    private $compte = null;
    
    public function __construct(Compte $compte,  $options = array(), $CSRFSecret = null) {
        $this->compte = $compte;
        parent::__construct($compte);
    }

    public function configure() {
        $this->setWidget('adresse_societe', new sfWidgetFormChoice(array('choices' => $this->getAdresseSociete(), 'expanded' => true, 'multiple' => false)));
        $this->widgetSchema->setLabel('adresse_societe', 'Même adresse que la société ?');
        $this->setValidator('adresse_societe', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getAdresseSociete()))));
        $this->widgetSchema->setNameFormat('compte_modification[%s]');
    }

    public function getAdresseSociete() {
        return array(1 => 'oui', 0 => 'non');
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($values['adresse_societe']) {
            $this->getObject()->updateWithAdresseSociete();
        }
    }
    
}