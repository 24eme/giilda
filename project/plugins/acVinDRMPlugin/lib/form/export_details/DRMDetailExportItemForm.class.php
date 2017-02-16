<?php

class DRMDetailExportItemForm extends DRMESDetailsItemForm {

    public function __construct(\acCouchdbJson $object, $options = array(), $CSRFSecret = null) {
        parent::__construct($object, $options, $CSRFSecret);
    }

    public function getFormName() {

        return "drm_detail_export_item";
    }

    public function getIdentifiantChoices() {

        return ConfigurationClient::getInstance()->getCountryList();
    }

    public function getPostValidatorClass() {

        return 'DRMDetailExportItemValidator';
    }

    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
    }
    
}
