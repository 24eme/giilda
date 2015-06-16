<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMReleveNonApurementItemFormµ
 *
 * @author mathurin
 */
class DRMReleveNonApurementItemsForm extends acCouchdbObjectForm {

    public function configure() {
        foreach ($this->getObject() as $keyNonApurement => $object) {
            $this->embedForm($keyNonApurement, new DRMReleveNonApurementItemForm($object,array('keyNonApurement' => $keyNonApurement)));
        }
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $releveNonApurementItemForm) {
            $releveNonApurementItemForm->updateObject($values[$key]);
        }
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        foreach ($this->embeddedForms as $key => $form) {
            if (!array_key_exists($key, $taintedValues)) {
                $this->unEmbedForm($key);
            }
        }
        foreach ($taintedValues as $key => $values) {
            if (!is_array($values) || array_key_exists($key, $this->embeddedForms)) {
                continue;
            }
            $this->embedForm($key, new DRMReleveNonApurementItemForm($this->getObject()->add(),array('keyNonApurement' => $key)));
        }
    }

    public function unEmbedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->getObject()->remove($key);
    }

}
