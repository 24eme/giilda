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
    
    public function configure()
    {
        parent::configure();
        
        $this->setWidget('identifiant', new sfWidgetFormInput());
        $this->setWidget('siret', new sfWidgetFormInput());
        $this->setWidget('raison_sociale', new sfWidgetFormInput());
        $this->setWidget('telephone', new sfWidgetFormInput());
       // $this->setWidget('etablissement', new sfWidgetFormChoice(array('choices' => $this->getEtablissements(),'expanded' => false)));
        $this->setValidator('identifiant', new sfValidatorString(array('required' => true)));
        $this->setValidator('siret', new sfValidatorString(array('required' => true)));
        $this->setValidator('raison_sociale', new sfValidatorString(array('required' => true)));
        $this->setValidator('telephone', new sfValidatorNumber(array('required' => true)));
      //  $this->setValidator('etablissement', new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getEtablissements()))));
    
        $this->widgetSchema->setLabel('identifiant', 'Identifiant : ');        
        $this->widgetSchema->setLabel('siret', 'Siret : ');
        $this->widgetSchema->setLabel('raison_sociale', 'Raison sociale : ');
        $this->widgetSchema->setLabel('telephone', 'Telephone : ');
                $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('societe[%s]');
    }

    public function getEtablissements() {
        return EtablissementClient::getInstance()->findAll();
    }
}

?>
