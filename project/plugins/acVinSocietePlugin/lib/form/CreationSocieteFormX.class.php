<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class SocieteCreationForm
 * @author mathurin
 */
class CreationSocieteFormX extends sfForm {
    
    public function configure()
    {
        parent::configure();
        $this->setWidget('societeType', new sfWidgetFormChoice(array('choices' => $this->getSocieteTypes(),'expanded' => false)));
        
        $this->setValidator('societeType', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getSocieteTypes()))));

        $this->widgetSchema->setLabel('societeType', 'Type de société : ');        
        
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('societe[%s]');
    }

    public function getSocieteTypes() {
        return SocieteClient::getSocieteTypes();
    }
}

?>
