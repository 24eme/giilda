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

    private $societe_types;
    
    public function __construct($societe_types = null, $defaults = array(), $options = array(), $CSRFSecret = null) {
        if($societe_types){
            $this->societe_types = $societe_types;
        }
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
        $societeTypes = SocieteClient::getInstance()->getSocieteTypes();
        if(!$this->societe_types){
            return $societeTypes;
        }
        
        $reel_societe_types = array();
        foreach ($societeTypes as $key_types => $types) {
            if (!is_array($types)){
                if(in_array($key_types, $this->societe_types)){
                    $reel_societe_types[$key_types] = $types;                
                }
            }
            else {
                foreach ($types as $sub_type_key => $entree) {
                    if(in_array($entree, $this->societe_types)){
                        if(!array_key_exists($key_types, $reel_societe_types)){
                            $reel_societe_types[$key_types] = array();
                        }
                           $reel_societe_types[$key_types][$sub_type_key] = $entree;                            
                    }
                }
            }
        }
        return $reel_societe_types;
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

?>
