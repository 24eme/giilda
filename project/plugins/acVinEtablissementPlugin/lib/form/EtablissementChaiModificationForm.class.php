<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class EtablissementChaiEditionForm
 * @author mathurin
 */
class EtablissementChaiModificationForm extends acCouchdbObjectForm {

    private $chai;

    public function __construct($chai, $options = array(), $CSRFSecret = null) {
        $this->chai = $chai;
        $this->chai->disableAutocalcule();
        parent::__construct($this->chai, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();

        $attributs = $this->getAttributs();

        $this->setWidget('nom', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('nom', 'Nom :');
        $this->setValidator('nom', new sfValidatorString(array('required' => false)));

        $this->setWidget('adresse', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('adresse', 'Adresse :');
        $this->setValidator('adresse', new sfValidatorString(array('required' => false)));

        $this->setWidget('commune', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('commune', 'Commune :');
        $this->setValidator('commune', new sfValidatorString(array('required' => false)));

        $this->setWidget('code_postal', new bsWidgetFormInput());
        $this->widgetSchema->setLabel('code_postal', 'Code Postal :');
        $this->setValidator('code_postal', new sfValidatorString(array('required' => false)));

        $this->setWidget('partage', new bsWidgetFormInputCheckbox());
        $this->widgetSchema->setLabel('partage', 'Partagé :');
        $this->setValidator('partage', new sfValidatorString(array('required' => false)));

        $this->setWidget('archive', new bsWidgetFormInputCheckbox());
        $this->widgetSchema->setLabel('archive', 'Archivé :');
        $this->setValidator('archive', new sfValidatorString(array('required' => false)));

        $this->setWidget('attributs', new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true, 'choices' => $attributs)));
        $this->widgetSchema->setLabel('attributs', 'Attributs :');
        $this->setValidator('attributs', new sfValidatorChoice(array('required' => false, 'multiple' => true, 'choices' => array_keys($attributs))));


        $this->widgetSchema->setNameFormat('etablissement_chai_modification[%s]');
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
    }

    public function doUpdateObject($values) {
      $attributs = array();
      if(isset($values["attributs"])) {
          foreach ($values["attributs"] as $attribut) {
            $attributs[$attribut] = EtablissementClient::$chaisAttributsLibelles[$attribut];
          }
      }
      $values["attributs"] = $attributs;
      $values['partage'] = (isset($values['partage']) && $values['partage']);
      $values["archive"] = intval(isset($values['archive']) && $values['archive']=="on"); 
      $toRemoves = array();
      foreach ($this->getObject()->attributs as $key => $attr) {
        if(!in_array($key,array_keys($values["attributs"]))){
          $toRemoves[] = $key;
        }
      }
      foreach ($toRemoves as $toRemove) {
        $this->getObject()->attributs->remove($toRemove);
      }
      parent::doUpdateObject($values);
    }

    public function getAttributs(){
      return EtablissementClient::$chaisAttributsLibelles;
    }


}
