<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMReleveNonAppurementItemFormµ
 *
 * @author mathurin
 */
class DRMReleveNonAppurementItemsForm extends acCouchdbObjectForm {

    public function configure() {
        foreach ($this->getObject() as $keyNonAppurement => $object) {
            $this->embedForm($keyNonAppurement, new DRMReleveNonAppurementItemForm($object,array('keyNonAppurement' => $keyNonAppurement)));
        }
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        foreach ($this->getEmbeddedForms() as $key => $releveNonAppurementItemForm) {
            $releveNonAppurementItemForm->updateObject($values[$key]);
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
            $this->embedForm($key, new DRMReleveNonAppurementItemForm($this->getObject()->add(),array('keyNonAppurement' => $key)));
        }
    }

    public function unEmbedForm($key) {
        unset($this->widgetSchema[$key]);
        unset($this->validatorSchema[$key]);
        unset($this->embeddedForms[$key]);
        $this->getObject()->remove($key);
    }

}
