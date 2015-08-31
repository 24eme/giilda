<?php

class DRMDetailCooperativeForm extends DRMESDetailsForm {

    public function __construct(acCouchdbJson $details, $defaults = array(), $options = array(), $CSRFSecret = null) {
        parent::__construct($details, $defaults, $options, $CSRFSecret);
    }

    public function getFormName() {

        return 'drm_detail_cooperative';
    }

    public function getFormItemClass() {

        return 'DRMDetailCooperativeItemForm';
    }

    public function getFormTemplateClass() {

        return 'DRMDetailCooperativeTemplateForm';
    }

}
