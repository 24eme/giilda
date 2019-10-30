<?php

class DRMDetailReintegrationItemForm extends DRMESDetailsItemForm {

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function configure() {

        $this->setWidget('identifiant', new bsWidgetFormInputDate());
        $this->setWidget('volume', new bsWidgetFormInputFloat(array(), array('autocomplete' => 'off')));

        $this->setValidator('identifiant', new sfValidatorString(array('required' => false)));
        $this->setValidator('volume', new sfValidatorNumber(array('required' => true, 'min' => 0), array('min' => "La saisie d'un nombre négatif est interdite")));


        $this->widgetSchema->setNameFormat(sprintf("%s[%%s]", $this->getFormName()));
    }

    public function getFormName() {

        return "drm_detail_reintegration_item";
    }

    public function getPostValidatorClass() {

        return 'DRMDetailReintegrationItemValidator';
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }

}
