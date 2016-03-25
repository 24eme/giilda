<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class EnseigneItemForm
 * @author mathurin
 */
class EnseigneItemForm extends acCouchdbObjectForm {

    public function configure() {
        $this->setWidget('label', new sfWidgetFormInput());
        $this->setValidator('label', new sfValidatorString(array('required' => false)));
        $this->widgetSchema->setLabel('label', 'Enseigne');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('enseigne[%s]');
    }
}
