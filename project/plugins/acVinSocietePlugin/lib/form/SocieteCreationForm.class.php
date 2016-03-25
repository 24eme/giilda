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

    public function __construct($societe_types = null, $defaults = array(), $options = array(), $CSRFSecret = null) {
        if($societe_types){
            $this->societe_types = $societe_types;
        }
        parent::__construct($defaults, $options, $CSRFSecret);
    }


    public function configure() {
        parent::configure();

        $this->setWidget('raison_sociale', new bsWidgetFormInput());
        $this->setWidget('type', new bsWidgetFormChoice(array('choices' => $this->getSocieteTypes(), 'expanded' => false)));

        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('type', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));

        $this->widgetSchema->setLabel('raison_sociale', 'Raison sociale de la société : ');
        $this->widgetSchema->setLabel('type', 'Type de société : ');


        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('societe-creation[%s]');
    }

    public function getSocieteTypes() {
        $societeTypes = SocieteClient::getInstance()->getSocieteTypes();

        return $societeTypes;
    }

    public function getSocieteTypesValid() {
        $societeType = $this->getSocieteTypes();
        $types = array();
        foreach ($societeType as $types) {
            if (!is_array($types))
                $result[] = $types;
            else {
                foreach ($types as $entree) {
                    $result[] = $entree;
                }
            }
        }
        return $result;
    }
}
