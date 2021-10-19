<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureMouvementsEditionLignesForm
 *
 * @author mathurin
 */
class FactureMouvementsEditionLignesForm extends acCouchdbObjectForm {

    protected $interpro_id;
    protected $virgin_object = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if(isset($options['interpro_id'])) {
            $this->interpro_id = $options['interpro_id'];
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
    		$this->virgin_object = $this->getObject()->add('nouveau');
        foreach ($this->getObject() as $identifiant => $mvts) {
          $this->embedForm($identifiant, new FactureMouvementEditionLignesForm($mvts, array('interpro_id' => $this->interpro_id)));
        }
        $this->validatorSchema->setOption('allow_extra_fields', true);
        $this->widgetSchema->setNameFormat('mouvements[%s]');
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
      foreach ($this->embeddedForms as $key => $form) {
        if(!array_key_exists($key, $taintedValues)) {
          $this->unEmbedForm($key);
        }
      }
      foreach($taintedValues as $key => $values) {
        if(!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
          continue;
        }
        $this->embedForm($key, new FactureMouvementEditionLignesForm($this->getObject()->add('nouveau')));
      }
    }

    public function unEmbedForm($key) {
      unset($this->widgetSchema[$key]);
      unset($this->validatorSchema[$key]);
      unset($this->embeddedForms[$key]);
      $this->getObject()->remove($key);
    }

    public function offsetUnset($offset) {
      parent::offsetUnset($offset);
      if (!is_null($this->virgin_object)) {
              $this->virgin_object->delete();
      }
    }
}
