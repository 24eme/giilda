<?php

class DRMDetailCreationVracForm extends DRMESDetailsForm {

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($details, $defaults, $options, $CSRFSecret);
    }

    public function getFormName() {

        return 'drm_creationdetail_vrac';
    }

    public function getFormItemClass() {

        return 'DRMDetailCreationVracItemForm';
    }

    public function getFormTemplateClass() {

        return 'DRMDetailCreationVracTemplateForm';
    }

    public function getModelNode(){
      return 'DRMESDetailCreationVrac';
    }


    public function bind(array $taintedValues = null, array $taintedFiles = null) {
      parent::bind($taintedValues,$taintedFiles);
    }

    public function update(){
      parent::update();
    }

}
