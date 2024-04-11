<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteCreationForm
 * @author mathurin
 */
class SocieteCreationForm extends baseForm {

    private $societe_types;

    public function __construct($defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($defaults, $options, $CSRFSecret);
    }


    public function configure() {
        parent::configure();

        $this->setWidget('raison_sociale', new bsWidgetFormInput());

        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));

        $this->widgetSchema->setLabel('raison_sociale', 'Raison sociale de la société : ');

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('societe-creation[%s]');
    }
}
