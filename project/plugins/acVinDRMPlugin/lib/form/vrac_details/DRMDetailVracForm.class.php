<?php

class DRMDetailVracForm extends DRMESDetailsForm {

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($details, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
      parent::configure();
    }

    public function getFormName() {

        return 'drm_detail_vrac';
    }

    public function getFormItemClass() {

        return 'DRMDetailVracItemForm';
    }

    public function getFormTemplateClass() {

        return 'DRMDetailVracTemplateForm';
    }

    public function getModelNode(){
      return 'DRMESDetailVrac';
    }

    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        parent::bind($taintedValues,$taintedFiles);
    }

    public function update(){
      parent::update();
    }

}
