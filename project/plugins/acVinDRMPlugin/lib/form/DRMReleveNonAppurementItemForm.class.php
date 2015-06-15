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
class DRMReleveNonAppurementItemForm extends acCouchdbObjectForm {

    public function configure() {

        $this->setWidget('numero_document', new sfWidgetFormInput());
        $this->setWidget('date_emission', new sfWidgetFormInput());
        $this->setWidget('numero_accise', new sfWidgetFormInput());

        $this->widgetSchema->setLabel('numero_document', 'Numéro document');
        $this->widgetSchema->setLabel('date_emission', 'Date émission');
        $this->widgetSchema->setLabel('numero_accise', 'Numéro accise');

        $this->setValidator('numero_document', new sfValidatorString(array('required' => false)));
        $this->setValidator('date_emission', new sfValidatorString(array('required' => false)));
        $this->setValidator('numero_accise', new sfValidatorString(array('required' => false)));

        $this->widgetSchema->setNameFormat('releveNonAppurement[%s]');
    }

}
