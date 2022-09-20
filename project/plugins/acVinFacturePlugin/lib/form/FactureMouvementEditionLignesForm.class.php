<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactureMouvementEditionLignesForm
 *
 * @author mathurin
 */
class FactureMouvementEditionLignesForm extends acCouchdbObjectForm {

    protected $interpro_id;
    protected $region;
    protected $virgin_object = null;

    public function __construct(acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        if(isset($options['interpro_id'])) {
            $this->interpro_id = $options['interpro_id'];
        }
        if(isset($options['region'])) {
            $this->region = $options['region'];
        }
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {
        $this->virgin_object = $this->getObject()->mouvements->add('nouveau')->add('nouveau');
        $mvts = $this->getObject()->getSortedMvts();
        foreach ($mvts as $identifiant => $mvt) {
          if ($mvt->getKey() != 'nouveau' && $this->region && $mvt->region != $this->region) continue;
          $this->embedForm($identifiant, new FactureMouvementEtablissementEditionLigneForm($mvt, array('interpro_id' => $this->interpro_id, 'region' => $this->region)));
        }
        $this->validatorSchema->setOption('allow_extra_fields', true);
        $this->widgetSchema->setNameFormat('[%s]');
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
        $this->embedForm($key, new FactureMouvementEditionLignesForm($this->getObject()->mouvements->add('nouveau')->add('nouveau')));
      }
    }

    public function unEmbedForm($key) {
      unset($this->widgetSchema[$key]);
      unset($this->validatorSchema[$key]);
      unset($this->embeddedForms[$key]);
      $this->getObject()->mouvements->remove($key);
    }

    public function offsetUnset($offset) {
      parent::offsetUnset($offset);
      if (!is_null($this->virgin_object)) {
              $this->virgin_object->delete();
      }
    }

}
