<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteCreationForm
 * @author mathurin
 */
class SocieteCreationForm extends sfForm {

    
    public function __construct($raison_sociale = false, $defaults = array(), $options = array(), $CSRFSecret = null) {
        if($raison_sociale) $defaults['raison_sociale'] = $raison_sociale;
        parent::__construct($defaults, $options, $CSRFSecret);
    }


    public function configure() {
        parent::configure();

        $this->setWidget('raison_sociale', new sfWidgetFormInput());
        $this->setWidget('type', new sfWidgetFormChoice(array('choices' => $this->getSocieteTypes(), 'expanded' => false)));

        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('type', new sfValidatorChoice(array('required' => true, 'choices' => $this->getSocieteTypesValid())));

        $this->widgetSchema->setLabel('raison_sociale', 'Raison sociale de la société : ');
        $this->widgetSchema->setLabel('type', 'Type de société : ');


        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('societe-creation[%s]');
    }

    public function getSocieteTypes() {
        return SocieteClient::getInstance()->getSocieteTypes();
    }

    public function getSocieteTypesValid() {
        $societeType = SocieteClient::getInstance()->getSocieteTypes();
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

?>
