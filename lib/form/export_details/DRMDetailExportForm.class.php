<?php

class DRMDetailExportForm extends DRMESDetailsForm {

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
            parent::__construct($details, $defaults, $options, $CSRFSecret);
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
    
}