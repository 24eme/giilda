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
class DRMReleveNonApurementItemForm extends acCouchdbObjectForm {

    protected $keyNonApurement = null;


    public function __construct($object, $options = array(), $CSRFSecret = null) {
        $this->keyNonApurement = $options['keyNonApurement'];
        parent::__construct($object, $options, $CSRFSecret);
    }

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

        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
        $this->widgetSchema->setNameFormat('non_apurement[%s]');
    }


    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $numero_document = $values['numero_document'];
        $date_emission = $values['date_emission'];
        $numero_accise = trim($values['numero_accise']);
        if ($numero_document && $date_emission && $numero_accise) {
            $this->getObject()->getParent()->updateNonApurement($this->keyNonApurement, $numero_document, $date_emission, $numero_accise);
        }
    }

}
