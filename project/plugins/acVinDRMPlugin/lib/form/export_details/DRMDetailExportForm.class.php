<?php

class DRMDetailExportForm extends DRMESDetailsForm {

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($details, $defaults, $options, $CSRFSecret);
    }

    public function configure() {
        parent::configure();
    }

    public function getFormName() {

        return 'drm_detail_export';
    }

    public function getFormItemClass() {

        return 'DRMDetailExportItemForm';
    }

    public function getFormTemplateClass() {

        return 'DRMDetailExportTemplateForm';
    }

    public function getModelNode(){

        return 'DRMESDetailExport';
    }


    public function bind(array $taintedValues = null, array $taintedFiles = null) {
        parent::bind($taintedValues,$taintedFiles);
    }

    public function update(){
        parent::update();
    }
}
