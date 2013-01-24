<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class CompteExtendedModificationForm
 * @author mathurin
 */
class CompteExtendedModificationForm extends CompteModificationForm {

    public function __construct(Compte $compte, $options = array(), $CSRFSecret = null) {
        parent::__construct($compte, $options, $CSRFSecret);         
    }

    public function configure() {
        parent::configure();
        $this->setWidget('civilite', new sfWidgetFormChoice(array('choices' => $this->getCiviliteList())));
        $this->setWidget('nom', new sfWidgetFormInput());
        $this->setWidget('prenom', new sfWidgetFormInput());
        $this->setWidget('fonction', new sfWidgetFormInput());
        $this->setWidget('commentaire', new sfWidgetFormTextarea(array(), array('style' => 'width: 100%;resize:none;')));

        $this->widgetSchema->setLabel('civilite', 'Civilite *');
        $this->widgetSchema->setLabel('nom', 'Nom *');
        $this->widgetSchema->setLabel('prenom', 'Prenom');
        $this->widgetSchema->setLabel('fonction', 'Fonction *');
        $this->widgetSchema->setLabel('commentaire', 'Commentaire');

        $this->setValidator('civilite', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getCiviliteList()))));
        $this->setValidator('nom', new sfValidatorString(array('required' => true)));
        $this->setValidator('prenom', new sfValidatorString(array('required' => false)));
        $this->setValidator('fonction', new sfValidatorString(array('required' => true)));
        $this->setValidator('commentaire', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('compte_modification[%s]');
    }
    public function getCiviliteList() {
        return array('Mlle' => 'Mlle', 'Mme' => 'Mme', 'M' => 'M');
    }

}